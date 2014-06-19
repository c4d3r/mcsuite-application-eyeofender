<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 17/09/13
 * Time: 16:14
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Controller;


use Doctrine\ORM\Query;
use Maxim\Module\ForumBundle\Entity\Post;
use Maxim\Module\ForumBundle\Entity\Thread;
use Maxim\Module\ForumBundle\Entity\ThreadEdit;
use Maxim\Module\ForumBundle\Form\Type\PostFormType;
use Maxim\Module\ForumBundle\Form\Type\ThreadFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ThreadController extends Controller
{
    public function createAction($id)
    {
        $request = Request::createFromGlobals();
        $em      = $this->getDoctrine()->getEntityManager();
        $user    = $this->get('security.context')->getToken()->getUser();

        # search forum
        $forum = $em->getRepository('MaximModuleForumBundle:Forum')->findOneBy(array("id" => $id));
        if(!$forum) { $this->createNotFoundException('Could not find the requested forum'); }

        # create form
        $thread = new Thread($user, $forum);
        $form = $this->createForm(new ThreadFormType(), $thread);

        # handle form
        $form->handleRequest($request);
        if ($form->isValid()) {

            $thread = $form->getData();
            $thread->setLastPostCreator($user);
            $em->persist($thread);

            $forum->setLastPostCreator($user);
            $forum->addThreadCount(1);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your thread has been created!'
            );
            return $this->redirect($this->generateUrl('forum_thread_view', array('id' => $forum->getId(), 'threadid' => $thread->getId())));
        }

        # set vars
        $data['forum'] = $forum;
        $data['form']  = $form->createView();
        return $this->render('MaximCMSBundle:Forum:createThread.html.twig', $data);
    }

    public function editAction($id, $threadid)
    {
        $em = $this->getDoctrine();

        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $threadid));

        if(!$thread) {
            throw $this->createNotFoundException("Could not find the requested thread");
        }
        if($thread->getCreatedBy() != $this->getUser()) {
            throw new AccessDeniedException("You are not allowed to edit this thread");
        }

        $data['thread'] = $thread;

        return $this->render('MaximCMSBundle:Forum:editThread.html.twig', $data);
    }
    public function editAjaxAction($id, $threadid)
    {
        $em = $this->getDoctrine()->getManager();

        $logger = $this->get('logger');
        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $threadid));

        if(!$thread) {
            throw $this->createNotFoundException("Could not find the requested thread");
        }
        if($thread->getCreatedBy()->getId() != $this->getUser()->getId()) {
            throw new AccessDeniedException("You are not allowed to edit this thread");
        }

        $request = Request::createFromGlobals();
        $text = $request->request->get('_thread_text');

        $thread->setText(strip_tags($text));

        $reason = $request->request->get('_reason');

        $tu = new ThreadEdit();
        $tu->setReason(strip_tags($reason));
        $tu->setUpdatedBy($this->getUser());
        $tu->setThread($thread);

        # validate thread object and edit object
        $validator = $this->get('validator');
        $result = $validator->validate($thread);
        $result2 = $validator->validate($tu);

        $data['array'] = $result;
        if ((count($result) > 0)){
            return new Response(json_encode(array('success' => false, 'message' => $this->renderView('MaximCMSBundle:Exception:arrayToList.html.twig', $data))));
        }

        $data['array'] = $result2;
        if ((count($result2) > 0)){
            return new Response(json_encode(array('success' => false, 'message' => $this->renderView('MaximCMSBundle:Exception:arrayToList.html.twig', $data))));
        }

        $thread->setText($text);
        $tu->setReason($reason);
        $em->persist($tu);
        $em->flush();

        return new Response(json_encode(array("success" => true, "message" => "Your thread has been updated", "redirect" => $this->generateUrl(
            'forum_thread_view',
            array('id' => $thread->getForum()->getId(), 'threadid' => $threadid)
        ))));
    }

    public function viewAction($id, $threadid)
    {
        $em      = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();
        $user    = $this->get('security.context')->getToken()->getUser();

        # find thread
        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findThreadById($threadid);

        # find thread posts
        $query = $em->getRepository('MaximModuleForumBundle:Post')->findThreadPosts($thread->getId());

        # paginate the thread posts
        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        # send page to check if
        $page = $request->query->get('page');

        if(!($page == null))  {
            if(!($page == 1)) {
                $data['page'] = true;
            }
        }

        # create form
        $post = new Post($user, $thread);
        $form = $this->createForm(new PostFormType(), $post);

        # handle form
        $form->handleRequest($request);
        if ($form->isValid()) {

            $post = $form->getData();
            $em->persist($post);
            $em->flush();

            $forum = $thread->getForum();
            $forum->setLastPostCreator($user);
            $forum->addPostCount(1);
            $thread->addPostCount(1);
            $thread->setLastPostCreator($user);
            $forum->setLastPostCreator($user);
            $thread->setLastPost($post);
            $forum->setLastPost($post);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your post has been added!'
            );
            return $this->redirect($this->generateUrl('forum_thread_view', array('id' => $forum->getId(), 'threadid' => $thread->getId())));
        }

        $data['posts']  = $posts;
        $data['thread'] = $thread;
        $data['form']   = $form->createView();

        # admin tools
        if ($this->get('security.context')->isGranted('ROLE_STAFF'))
        {
            $data['forums'] = $em->getRepository('MaximModuleForumBundle:Forum')->findAllForums();
        }

        return $this->render('MaximCMSBundle:Forum:viewThread.html.twig', $data);
    }

    public function adminPinAjaxAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$this->get('security.context')->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException("Only staff members can pin threads");
        }

        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        # SEARCH THREAD
        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $id));
        if(!$thread) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested thread."))); }

        try {
            $thread->setPinned(!$thread->isPinned());

            $em->flush();

            return new Response(json_encode(array("success" => true, "message" => "The thread has been " . ($thread->isPinned() ? "pinned" : "unpinned"))));
        }catch(\Exception $ex) {
            $logger->error("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error occured, please try again later.")));
        }
    }
    public function adminArchiveAjaxAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$this->get('security.context')->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException("Only staff members can archive topics");
        }

        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        # SEARCH THREAD
        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $id));
        if(!$thread) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested thread."))); }

        try {
            $thread->setPinned(!$thread->isPinned());

            $em->flush();

            return new Response(json_encode(array("success" => true, "message" => "The thread has been " . ($thread->isPinned() ? "pinned" : "unpinned"))));
        }catch(\Exception $ex) {
            $logger->error("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error occured, please try again later.")));
        }
    }
    public function adminLockAjaxAction($id)
    {
        if (!$this->get('security.context')->isGranted('ROLE_ADMIN') && !$this->get('security.context')->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException("Only staff members can lock threads");
        }

        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        # SEARCH THREAD
        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $id));
        if(!$thread) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested thread."))); }

        # GET USER
        $user = $this->getUser();

        try {
            $thread->setLocked(!$thread->isLocked());
            $em->flush();
            // CREATE REPLY
            $post = new Post();

            if($thread->isLocked()) {
                $post->setText("Thread is locked by: " . $this->getUser()->getUsername());
            } else {
                $post->setText("Thread is re-opened by: " . $this->getUser()->getUsername());
            }

            $post->setCreatedBy($user);
            $post->setThread($thread);
            $em->persist($post);
            $em->flush();
            return new Response(json_encode(array("success" => true, "message" => "The thread has been " . ($thread->isLocked() ? "locked" : "unlocked"))));
        }catch(\Exception $ex) {
            $logger->error("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error occured, please try again later.")));
        }

    }

    public function adminMoveAjaxAction($id)
    {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') || false === $this->get('security.context')->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $request = Request::createFromGlobals();

        $forumIdTo = $request->request->get('_forumto');

        # SEARCH THREAD
        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $id));
        if(!$thread) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested thread."))); }

        # SEARCH FORUM TO
        $forum = $em->getRepository('MaximModuleForumBundle:Forum')->findOneBy(array("id" => $forumIdTo));
        if(!$forum) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested forum."))); }

        try {
            $thread->setForum($forum);

            $em->flush();

            return new Response(json_encode(array("success" => true, "message" => "The thread has been moved to " . $forum->getTitle())));
        }catch(\Exception $ex) {
            $logger->error("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error occured, please try again later.")));
        }
    }

}