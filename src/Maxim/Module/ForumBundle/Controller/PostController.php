<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 18/09/13
 * Time: 15:27
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Controller;

use Maxim\Module\ForumBundle\Entity\Post;
use Maxim\Module\ForumBundle\Entity\PostEdit;
use Maxim\Module\ForumBundle\Form\Type\PostEditType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Maxim\Module\ForumBundle\Entity\PostLike;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostController extends Controller
{
    public function likeAjaxAction($id)
    {
        $request = Request::createFromGlobals();
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        # GET POST
        $post = $em->getRepository('MaximModuleForumBundle:Post')->findOneBy(array("id" => $id));
        if(!$post) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested post."))); }

        # CREATE LIKE
        try
        {
            $like = new PostLike();
            $like->setPost($post);
            $like->setLikedBy($this->getUser());
            $em->persist($like);
            $em->flush();

            return new Response(json_encode(array("success" => true, "message"  => "The post has been liked!")));
        }
        catch(\Exception $ex)
        {
            $logger->error("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "Can not like this post at this moment, please try again later or contact an administrator")));
        }
    }
    public function editAction($id, $threadid, $postid)
    {
        $request = Request::createFromGlobals();
        $em      = $this->getDoctrine()->getManager();
        $user    = $this->get('security.context')->getToken()->getUser();

        # get thread and validate
        $post = $em->getRepository('MaximModuleForumBundle:Post')->findOneBy(array("id" => $postid));

        if(!$post) {
            throw $this->createNotFoundException("Could not find the requested post");
        }
        if($post->getCreatedBy()->getId() != $user->getId()) {
            throw new AccessDeniedException("You are not allowed to edit this post");
        }

        # create form
        $postedit = new PostEdit($post, $user);
        $form = $this->createForm(new PostEditType(), $postedit);

        # handle form
        $form->handleRequest($request);
        if ($form->isValid()) {

            $postedit = $form->getData();
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'notice',
                'Your post has been updated!'
            );
            return $this->redirect($this->generateUrl('forum_thread_view', array('id' => $post->getThread()->getForum()->getId(), 'threadid' => $post->getThread()->getId())));
        }

        # set vars
        $data['form']  = $form->createView();
        $data['post'] = $post;

        return $this->render('MaximCMSBundle:Forum:editPost.html.twig', $data);
    }
}