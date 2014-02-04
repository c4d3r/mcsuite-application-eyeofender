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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Maxim\Module\ForumBundle\Entity\PostLike;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PostController extends Controller{

    public function replyAction($id, $threadid)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $request = Request::createFromGlobals();

        # SEARCH THREAD
        $thread = $em->getRepository('MaximModuleForumBundle:Thread')->findOneBy(array("id" => $threadid));
        if(!$thread) { return new Response(json_encode(array("success" => false, "message" => "Could not find the requested thread."))); }

        $reply_text = $request->request->get('_post_reply_text');
        $user = $this->getuser();

        # create the object
        $post = new Post();
        $post->setText(strip_tags($reply_text));
        $post->setCreatedBy($user);
        $post->setThread($thread);

        # validate thread object
        $validator = $this->get('validator');
        $result = $validator->validate($post);
        $data['array'] = $result;

        if ((count($result) > 0)){
            return new Response(json_encode(array('success' => false, 'message' => $this->renderView('MaximCMSBundle:Exception:arrayToList.html.twig', $data))));
        }

        $post->setText($reply_text);

        $lastPost = $em->getRepository("MaximModuleForumBundle:Post")->findLatestPost($this->getUser());
        if(isset($lastPost[0]) && (false === $this->get('security.context')->isGranted('ROLE_STAFF')))
        {
            // check time difference
            $diff = $lastPost[0]->getCreatedOn()->diff(new \DateTime("now"));
            $threshold = $this->container->getParameter("maxim_module_forum.posts.threshold");
            if(!($diff->days > 0 || $diff->i > $threshold))
            {
                return new Response(json_encode(array("success" => false, "message" => sprintf("please wait %d %s before posting a new post",
                    $threshold,
                    $threshold > 1 ? "minutes" : "minute"
                ))));
            }
        }

        # CREATE POST
        try {

            $em->persist($post);
            $thread->setLastPost($post);
            $thread->getForum()->setLastPost($post);
            $thread->setLastPostCreator($this->getUser());
            $thread->getForum()->setLastPostCreator($this->getUser());
            $thread->getForum()->addPostCount(1);
            $thread->addPostCount(1);
            $em->flush();

            return new Response(json_encode(array("success" => true, "message"  => "Your post has been added succesfully!")));

        }catch(\Exception $ex) {
            $logger->err("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "Can not reply at this moment, please try again later or contact an administrator")));
        }
    }

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
            $logger->err("[FORUM]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "Can not like this post at this moment, please try again later or contact an administrator")));
        }
    }
    public function editAction($id, $threadid, $postid)
    {
        $em = $this->getDoctrine();

        $post = $em->getRepository('MaximModuleForumBundle:Post')->findOneBy(array("id" => $postid));

        if(!$post) {
            throw $this->createNotFoundException("Could not find the requested thread");
        }
        if($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
            throw new AccessDeniedException("You are not allowed to edit this thread");
        }

        $data['post'] = $post;

        return $this->render('MaximCMSBundle:Forum:editPost.html.twig', $data);
    }
    public function editAjaxAction($id, $threadid, $postid)
    {
        $em = $this->getDoctrine()->getManager();

        $post = $em->getRepository('MaximModuleForumBundle:Post')->findOneBy(array("id" => $postid));

        if(!$post) {
            throw $this->createNotFoundException("Could not find the requested thread");
        }
        if($post->getCreatedBy()->getId() != $this->getUser()->getId()) {
            throw new AccessDeniedException("You are not allowed to edit this thread");
        }

        $request = Request::createFromGlobals();
        $text = $request->request->get('_post_text');

        $post->setText(strip_tags($text));

        $pu = new PostEdit();
        $reason = $request->request->get('_reason');
        $pu->setReason(strip_tags($reason));
        $pu->setUpdatedBy($this->getUser());
        $pu->setPost($post);

        # validate thread object and edit object
        $validator = $this->get('validator');
        $result = $validator->validate($post);
        $result2 = $validator->validate($pu);

        $data['array'] = $result;
        if ((count($result) > 0)){
            return new Response(json_encode(array('success' => false, 'message' => $this->renderView('MaximCMSBundle:Exception:arrayToList.html.twig', $data))));
        }

        $data['array'] = $result2;
        if ((count($result2) > 0)){
            return new Response(json_encode(array('success' => false, 'message' => $this->renderView('MaximCMSBundle:Exception:arrayToList.html.twig', $data))));
        }

        $pu->setReason($reason);
        $post->setText($text);

        $em->persist($pu);
        $em->flush();

        return new Response(json_encode(array("success" => true, "message" => "Your post has been updated", "redirect" => $this->generateUrl(
            'forum_thread_view',
            array('id' => $id, 'threadid' => $threadid)
        ))));
    }
}