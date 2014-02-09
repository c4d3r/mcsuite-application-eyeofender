<?php
/**
 * Author: Maxim
 * Date: 09/02/14
 * Time: 13:42
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class NotificationController extends Controller
{
    public function readNotificationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id     = $request->request->get('_id');
        $user   = $this->getUser();

        $notification = $em->getRepository('MaximCMSBundle:UserNotification')->findOneBy(array("id" => $id, "receiver" => $user));

        if(!$notification->isRead()) {
            $notification->setReadOn(new \DateTime("now"));
        }

        $em->flush();

        return new Response("Notification marked as read");
    }
} 