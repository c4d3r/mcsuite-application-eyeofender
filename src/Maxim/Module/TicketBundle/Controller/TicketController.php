<?php
/**
 * Project: MCSuite
 * File: TicketController.php
 * User: Maxim
 * Date: 03/04/13
 * Time: 13:23
 */
namespace Maxim\Module\TicketBundle\Controller;

use Maxim\CMSBundle\Controller\ModuleController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Maxim\Module\TicketBundle\Entity\Ticket;
use Maxim\Module\TicketBundle\Entity\TicketReply;
use Maxim\Module\TicketBundle\Entity\TicketSection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TicketController extends Controller
{
    public function indexAction()
    {
        $doctrine = $this->getDoctrine()->getManager();
        $data['tickets']  = $doctrine->getRepository('MaximModuleTicketBundle:Ticket')->findBy(array("user" => $this->getUser()));
        $data['sections'] = $doctrine->getRepository('MaximModuleTicketBundle:TicketSection')->findAll();

        return $this->render('MaximCMSBundle:Module:tickets/tickets.html.twig', $data);
    }

    public function createAction()
    {
        $request = Request::createFromGlobals();
        $logger = $this->get('logger');

        if($request->isXmlHttpRequest())
        {
            try
            {
                $bbcode = $this->get('bbcode.helper');

                $em = $this->getDoctrine()->getManager();

                $description = $request->request->get('_description');
                $section     = $request->request->get('_section');

                $section = $em->getRepository('MaximModuleTicketBundle:TicketSection')->findOneBy(array("id" => $section));
                $user = $this->getUser();

                $ticket = new Ticket(time() . $user->getId());

                $ticket->setDescription($bbcode->parse($description));
                $ticket->setSection($section);
                $ticket->setUser($this->getUser());
                $ticket->setStatus("Waiting for response from a moderator");
                $ticket->setWebsite($em->getRepository("MaximCMSBundle:Website")->findOneBy(array("id" => $this->container->getParameter("website"))));

                $em->persist($ticket);
                $em->flush();

                $output = array("success" => true, "message" => "Your ticket has been created succesfuly");

            }
            catch(\Exception $ex)
            {
                $logger->err("MODULE TICKET: " . $ex->getMessage());
                $output = array("success" => false, "message" => "An error has occured while creating this ticket, please try again later");
            }
            return new Response(json_encode($output));
        }
    }

    public function viewTicketAction($id)
    {
        $logger = $this->get('logger');
        $data['ticket'] = $this->getDoctrine()->getManager()->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $id));
        if(count($data['ticket']) == 0)
        {
            //Not found
            return $this->render("MaximCMSBundle:Module:tickets/notFound.html.twig");
        }
        $data['replies'] = $this->getDoctrine()->getManager()->getRepository('MaximModuleTicketBundle:TicketReply')->findBy(array("ticket" => $data['ticket']));

        if($this->isTicketOwner($data['ticket'], $this->getUser()))
        {
            return $this->render("MaximCMSBundle:Module:tickets/view.html.twig", $data);
        }
        else
        {
            return $this->render("MaximCMSBundle:Module:tickets/notOwner.html.twig");
        }
    }
    public function closeAction()
    {
        $request = Request::createFromGlobals();
        if($request->isXmlHttpRequest())
        {
            $em         = $this->getDoctrine()->getManager();
            $ticketid   = $request->request->get('_ticket');
            $ticket     = $em->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $ticketid));

            if($this->hasPermission())
            {
                $ticket->setClosed(1);
                $ticket->setStatus("The ticket has been closed");
                $em->flush();
                $output = array("success" => true, "message" => "The ticket has been closed by a moderator");
            }
            else if($this->isTicketOwner($ticket, $this->getUser()))
            {
                $ticket->setClosed(1);
                $ticket->setStatus("The ticket has been closed");
                $em->flush();
                $output = array("success" => true, "message" => "The ticket has been closed");
            }
            else
            {
                $output = array("success" => false, "message" => "this is not your ticket");
            }
        }
        else
        {
            $output = array("success" => false, "message" => "Is not a post request");
        }
        return new Response(json_encode($output));

    }
    public function openAction()
    {
        $request = Request::createFromGlobals();
        if($request->isXmlHttpRequest())
        {
            $em         = $this->getDoctrine()->getManager();
            $ticketid   = $request->request->get('_ticket');
            $ticket     = $em->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $ticketid));
            if($this->isTicketOwner($ticket, $this->getUser()) || $this->hasPermission())
            {
                $ticket->setClosed(0);
                $ticket->setStatus("The ticket has been reopened");
                $em->flush();
                $output = array("success" => true, "message" => "The ticket has been reopened");
            }
            else
            {
                $output = array("success" => false, "message" => "this is not your ticket");
            }
        }
        else
        {
            $output = array("success" => false, "message" => "Is not a post request");
        }
        return new Response(json_encode($output));

    }
    public function replyAction()
    {
        $request = Request::createFromGlobals();
        $logger = $this->get('logger');

        if($request->isXmlHttpRequest())
        {
            try
            {
                $bbcode = $this->get('bbcode.helper');
                $em = $this->getDoctrine()->getManager();

                $text       = $request->request->get('_reply');
                $user       = $this->get('security.context')->getToken()->getUser();
                $ticketid   = $request->request->get('_ticket');
                $ticket     = $em->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $ticketid));

                if($user)
                {
                    if($this->hasPermission())
                    {
                        $reply = new TicketReply();
                        $reply->setText($bbcode->parse($text));
                        $reply->setUser($user);
                        $reply->setTicket($ticket);
                        $ticket->setStatus("Waiting from a response from the ticket owner");
                        $em->persist($reply);
                        $em->flush();

                        $output = array("success" => true, "message" => "Your reply has been added");
                    }
                    else if($this->isTicketOwner($ticket, $user))
                    {
                        $reply = new TicketReply();
                        $reply->setText($bbcode->parse($text));
                        $reply->setUser($user);
                        $reply->setTicket($ticket);
                        $ticket->setStatus("Waiting from a response from a moderator");
                        $em->persist($reply);
                        $em->flush();

                        $output = array("success" => true, "message" => "Your reply has been added");
                    }
                    else
                    {
                        $output = array("success" => false, "message" => "This is not your ticket");
                    }
                }
                else
                {
                    $output = array("success" => false, "message" => "We were unable to find the user account");
                }
            }
            catch(\Exception $ex)
            {
                $logger->err("MODULE TICKET: " . $ex->getMessage());
                $output = array("success" => false, "message" => "An error has occured while adding your reply, please try again later");
            }
            return new Response(json_encode($output));
        }
    }
    public function isTicketOwner($ticket, $user)
    {
        return ($ticket->getUser()->getId() == $user->getId());
    }
    public function hasPermission()
    {
        $security = $this->get('security.context');
        return ($security->isGranted('ROLE_STAFF') || $security->isGranted('ROLE_ADMIN'));
    }
}