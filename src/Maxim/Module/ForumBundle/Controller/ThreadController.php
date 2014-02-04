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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ThreadController extends Controller{

    public function createAction($id) {

        $em = $this->getDoctrine();

        # SEARCH FORUM
        $forum = $em->getRepository('MaximModuleForumBundle:Forum')->findOneBy(array("id" => $id));
        if(!$forum) { $this->createNotFoundException('Could not find the requested forum'); }

        # SHOW FORM
        $data['forum'] = $forum;
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

    public function createAjaxAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        # GET FIELDS
        $forumid    = $request->request->get('_forumid');
        $threadText  = $request->request->get('_thread_text');
        $threadTitle = $request->request->get('_thread_title');
        # GET USER
        $user = $this->getUser();
        if(!$user){
            return new Response(json_encode(array("success" => false, "message" => "You must be logged in to use this feature!")));
        }

        # SEARCH FORUM
        $forum = $em->getRepository('MaximModuleForumBundle:Forum')->findOneBy(array("id" => $forumid));
        if(!$forum) { return new Response(json_encode(array("success" => false, "message" => "Could not find the forum you are trying to create a thread for."))); }

        # exploit prevention
        if($forum->getShowOnHome() == true && !$this->get('security.context')->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException("Only staff members can create news threads in this category!");
        }

        # create Thread object
        $thread = new Thread();
        $thread->setText(strip_tags($threadText));
        $thread->setTitle(strip_tags($threadTitle));
        $thread->setCreatedBy($user);
        $thread->setForum($forum);
        $thread->setLastPostCreator($this->getUser());
        $forum->setLastPostCreator($this->getUser());

        # validate thread object
        $validator = $this->get('validator');
        $result = $validator->validate($thread);
        $data['array'] = $result;

        if ((count($result) > 0)){
            return new Response(json_encode(array('success' => false, 'message' => $this->renderView('MaximCMSBundle:Exception:arrayToList.html.twig', $data))));
        }

        $thread->setTitle($threadTitle);
        $thread->setText($threadText);

        # spam protection, max every xx min
        $lastThread = $em->getRepository("MaximModuleForumBundle:Thread")->findLatestThread($user);
        if(isset($lastThread[0]) && (false === $this->get('security.context')->isGranted('ROLE_STAFF')))
        {
            // check time difference
            $diff = $lastThread[0]->getCreatedOn()->diff(new \DateTime("now"));
            if(!($diff->days > 0 || $diff->i > 3))
            {
                $threshold = $this->container->getParameter("maxim_module_forum.threads.threshold");
                return new Response(json_encode(array("success" => false, "message" => sprintf("please wait %d %s before posting a new thread",
                    $threshold,
                    $threshold > 1 ? "minutes" : "minute"
                ))));
            }
        }

        # CREATE THREAD
        try {
            $em->persist($thread);
            $forum->addThreadCount(1);
            $em->flush();
        }catch(\Exception $ex) {
            $logger->err("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error has occured while creating your thread, please try again later.")));
        }

        return new Response(json_encode(array(
                "success"   => true,
                "message"   => "Your thread has been created succesfully!",
                "redirect"  => $this->generateUrl('forum_thread_view', array(
                    'threadid'   => $thread->getId(),
                    "id"        => $forum->getId()
                ))
            )
        ));
    }

    public function viewAction($id, $threadid)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        # SEARCH THREAD
       // $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $threadid));
        //if(!$thread) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested thread."))); }

        $query = $em->createQuery(
            "SELECT t, f, u, g, p
            FROM MaximModuleForumBundle:Thread t
            JOIN t.forum f
            JOIN t.createdBy u
            LEFT JOIN u.groups g
            LEFT JOIN u.posts p
            WHERE t.id = :id
            "
        )
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->setParameter("id", $threadid);
        $query->useResultCache(true, 10, __METHOD__ . serialize($query->getParameters()));
        $thread = $query->getSingleResult();

        # SEARCH POSTS
        $query = $em->getRepository('MaximModuleForumBundle:Post')->findBy(array("thread" => $thread));
        $query = $em->createQuery(
            "SELECT p, u, l, g
            FROM MaximModuleForumBundle:Post p
            JOIN p.createdBy u
            LEFT JOIN u.groups g
            JOIN p.thread t
            LEFT JOIN p.likes l
            WHERE t.id = :id
            "
        )
            ->setParameter("id", $threadid)
            ->setHint(Query::HYDRATE_OBJECT, true)
        ;
        //$query->useResultCache(true, 5, __METHOD__ . serialize($query->getParameters()));

        $paginator  = $this->get('knp_paginator');
        $posts = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        $page = $request->query->get('page');

        if(!($page == null))  {
            if(!($page == 1)) {
                $data['page'] = true;
            }
        }

        $data['posts']  = $posts;
        $data['thread'] = $thread;

        // ADMIN TOOLS
        if ($this->get('security.context')->isGranted('ROLE_STAFF')) {

            $query = $em->createQuery(
                "SELECT f, c, u
                FROM MaximModuleForumBundle:Forum f
                LEFT JOIN f.category c
                LEFT JOIN f.createdBy u
                "
            )->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

            $query->useResultCache(true, 3600, __METHOD__ . serialize($query->getParameters()));

            $data['forums'] = $query->getResult();
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
            $logger->err("[FORUM]" . $ex->getMessage());
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
            $logger->err("[FORUM]" . $ex->getMessage());
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
            $logger->err("[FORUM]" . $ex->getMessage());
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
        $request = $this->getRequest();

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
            $logger->err("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error occured, please try again later.")));
        }
    }

}