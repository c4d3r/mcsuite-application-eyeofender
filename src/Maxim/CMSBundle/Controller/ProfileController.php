<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Maxim
 * Date: 26/06/13
 * Time: 12:07
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Controller;
use Maxim\CMSBundle\Entity\FriendRequest;
use Maxim\CMSBundle\Entity\Notification;
use Maxim\CMSBundle\Entity\UserFriend;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Maxim\CMSBundle\Entity\User;
use Symfony\Component\Form\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use FOS\UserBundle\Controller\ProfileController as BaseProfileController;

class ProfileController extends BaseProfileController
{

    public function viewAction($name)
    {
        $user = $this->container->get('doctrine')->getRepository('MaximCMSBundle:User')->findOneBy(array("username" => $name));

        if(!$user) {
            throw new NotFoundHttpException(sprintf('Could not find a user with username: %s', $name));
        }
        $data['player'] = $user;

        return $this->container->get('templating')->renderResponse('MaximCMSBundle:pages:profile.html.twig', $data);
    }

    public function sendFriendRequestAction(Request $request) {

        $em = $this->container->get('doctrine')->getManager();
        $logger = $this->container->get('logger');

        # GET PARAMS
        $recipientid = $request->request->get('_recipient');
        $userid      = $request->request->get('_user');

        # GET USERs
        $user      = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $userid));
        $recipient = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $recipientid));

        # EXCEPTIONS
        if(!$user) {
            $logger->err("Could not find requesting user");
            throw new NotFoundHttpException("Could not find requesting user");
        }
        if(!$recipient) {
            $logger->err("Could not find receiving user");
            throw new NotFoundHttpException("Could not find receiving user");
        }

        # CREATE REQUEST
        try {
            $fr = new FriendRequest();
            $fr->setUser($user);
            $fr->setRecipient($recipient);
            $fr->setState(FriendRequest::STATE_PENDING);
            $em->persist($fr);

            # make a notification
            $notification = new Notification();
            $notification->setUser($recipient);
            $notification->setType($notification::TYPE_FRIENDREQUEST);
            $notification->setText(sprintf("%s has sent you a friend request", $user->getUsername()));
            $em->persist($notification);

            # force flush
            $em->flush();

        }catch(\Exception $ex) {
            $logger->err("[FRIEND]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error has occured, please try again later.")));
        }

        return new Response(json_encode(array("success" => true, "message" => "Request has been sent!")));
    }

    public function deleteFriendAction(Request $request)
    {
        $securityContext = $this->container->get('security.context');

        if(!$request->isXmlHttpRequest()){
            throw new AccessDeniedException("Access denied: Invalid request!");
        }

        if( !$securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            throw new AccessDeniedException("You must be logged in to delete friends!");
        }

        # init vars
        $em     = $this->container->get('doctrine')->getManager();
        $user   = $this->container->get('security.context')->getToken()->getUser();
        $logger = $this->container->get('logger');

        try
        {
            # get friend and remove the current user from his list of friends
            $friend = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $request->request->get('_friend')));

            # get frequest FROM FRIEND
            $frequest = $em->getRepository('MaximCMSBundle:UserFriend')->findOneBy(array("user" => $user, "friend" => $friend));
            $urequest = $em->getRepository('MaximCMSBundle:UserFriend')->findOneBy(array("user" => $friend, "friend" => $user));

            # get friendrequest and delete it
            $friendrequest = $em->getRepository('MaximCMSBundle:FriendRequest')->findOneBy(array("recipient" => $friend, "user" => $user));

            if(!$friendrequest)
            {
                $friendrequest = $em->getRepository('MaximCMSBundle:FriendRequest')->findOneBy(array("recipient" => $user, "user" => $friend));
            }

            # remove friend from current user his list of friends
            $user->removeFriend($friend);
            $friend->removeFriend($user);

            $em->remove($friendrequest);
            $em->remove($frequest);
            $em->remove($urequest);

            $em->flush();

            return new Response(json_encode(array("success" => true, "message" => sprintf("You have deleted %s from your friend list", $friend->getUsername()))));
        }
        catch(\Exception $ex)
        {
            $logger->err("[FRIEND]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "An error occurred when deleting the friend, please try again later")));
        }

    }
    public function viewFriendrequestsAction(Request $request)
    {
        $em = $this->container->get('doctrine')->getManager();

        # get friend requests
        $frequests = $em->getRepository('MaximCMSBundle:FriendRequest')->findBy(array("recipient" => $this->container->get('security.context')->getToken()->getUser(), "state" => FriendRequest::STATE_PENDING));

        # put in data
        $data['frequests'] = $frequests;

        return $this->container->get('templating')->renderResponse('MaximCMSBundle:Account:friendrequests.html.twig', $data);
    }

    public function friendRequestAction($type)
    {
        $request = Request::createFromGlobals();

        if(!$request->isXmlHttpRequest()){
            throw new AccessDeniedException("Access denied!");
        }

        $em = $this->container->get('doctrine')->getManager();

        # get user to accept
        $userid = $request->request->get('_userid');
        $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $userid));

        if(!$user) {
            throw new NotFoundHttpException("User could not be found.");
        }

        # get the friendrequest
        $frequest = $em->getRepository('MaximCMSBundle:FriendRequest')->findOneBy(array("recipient" => $this->container->get('security.context')->getToken()->getUser(), "user" => $user));
        if(!$frequest || in_array($frequest->getState(), array(FriendRequest::STATE_ACCEPT, FriendRequest::STATE_DENY))) {
            return new Response(json_encode(array("success" => false, "userid" => $user->getId(), "message" => "Could not find the request, possibly the friend has already been accepted or denied.")));
        }

        try
        {
            if(strtoupper($type) == "APPROVE")
            {
                $frequest->setState(FriendRequest::STATE_ACCEPT);
                $frequest->setChangedOn(new \DateTime("now"));

                # add the friend to the receiving user
                $friend = new UserFriend();
                $friend->setUser($this->container->get('security.context')->getToken()->getUser());
                $friend->setFriend($user);
                $em->persist($friend);

                # add the friend to the requesting user
                $friend = new UserFriend();
                $friend->setUser($user);
                $friend->setFriend($this->container->get('security.context')->getToken()->getUser());
                $em->persist($friend);

                # notify the user who sent the request
                $notification = new Notification();
                $notification->setUser($frequest->getUser());
                $notification->setType(Notification::TYPE_FRIENDREQUEST);
                $notification->setText(sprintf("%s accepted your friend request", $frequest->getRecipient()->getUsername()));
                $em->persist($notification);
            }
            else
            {
                $frequest->setState(FriendRequest::STATE_DENY);
                $frequest->setChangedOn(new \DateTime("now"));
            }

            $em->flush();
        }
        catch(\Exception $ex)
        {
            $this->container->get('logger')->err($ex->getMessage());
            return new Response(json_encode(array("success" => false, "userid" => $user->getId(), "message" => "An error occured, please try again later")));
        }

        return new Response(json_encode(array("success" => true, "userid" => $user->getId())));
    }

}