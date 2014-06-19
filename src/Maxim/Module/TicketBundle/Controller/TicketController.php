<?php
/**
 * Author: Maxim
 * Date: 12/06/2014
 * Time: 20:32
 * Property of MCSuite
 */

namespace Maxim\Module\TicketBundle\Controller;

use Maxim\CMSBundle\Controller\ModuleController;
use Maxim\Module\TicketBundle\Entity\Ticket;
use Maxim\Module\TicketBundle\Entity\TicketHistory;
use Maxim\Module\TicketBundle\Entity\TicketReply;
use Maxim\Module\TicketBundle\Entity\UserTicket;
use Maxim\Module\TicketBundle\Form\Type\ReplyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class TicketController extends ModuleController
{

    public function indexAction()
    {
        $em = $this->getDoctrine();

        $data['tickets'] = $em->getRepository('MaximModuleTicketBundle:UserTicket')->findBy(array("user" => $this->getUser()));
        $data['ticketForms'] = $em->getRepository('MaximModuleTicketBundle:Ticket')->findBy(array("enabled" => true));
        return $this->render('Module:Ticket/tickets.html.twig', $data);
    }

    public function indexCreateAction()
    {
        $request = Request::createFromGlobals();

        $ticketid = $request->request->get("_section");
        $ticket = $this->getDoctrine()->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $ticketid));

        $data['id'] = $ticketid;
        $data['name'] = $ticket->getName();

        return $this->redirect($this->generateUrl("tickets_create_view", $data));
    }

    public function viewCreateAction($id, $name)
    {
        #init vars
        $em = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();

        #action
        $ticket = $em->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $id));
        if(!$ticket)
            throw $this->createNotFoundException("We could not find the requested ticket form");

        $formBuilder = $this->createFormBuilder();

        $fields = $ticket->getFields();
        foreach($fields as $key => $field)
        {
            $formBuilder->add($key, ($field[Ticket::FIELD_TYPE] == "textfield" ? "text" : "textarea"), array('label' => $field[Ticket::FIELD_NAME]));
        }

        # regular actions
        $formBuilder->add('Open ticket', 'submit');
        $form = $formBuilder->getForm();

        # handle action
        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            # build details
            $details = array();
            foreach($data as $key => $detail)
            {
                $details[$fields[$key][Ticket::FIELD_NAME]] = $detail;
            }

            $ut = new UserTicket($this->getUser(), $details, $ticket);
            $em->persist($ut);

            $th = new TicketHistory($ut, TicketHistory::TYPE_NEW, $this->getUser());
            $em->persist($th);

            $em->flush();

            $this->get('session')->getFlashBag()->set('notice', 'Your Ticket has been created and was submitted to our staff team');

            return $this->redirect($this->generateUrl("ticket_view", array('id' => $ut->getId(), "name" => $ticket->getName())));
        }

        return $this->render("Module:Ticket/create.html.twig", array(
            'form' => $form->createView(),
        ));
    }

    public function viewAction($id, $name)
    {
        $replyForm = $this->createForm(new ReplyType());

        $request = Request::createFromGlobals();

        if ( $request->isMethod( 'POST' ) ) {

            $replyForm->bind( $request );

            if ( $replyForm->isValid( ) ) {

                /*
                 * $data['title']
                 * $data['body']
                 */
                $data = $replyForm->getData();

                //create ticket reply
                $em = $this->getDoctrine()->getManager();

                $ticket = $em->getRepository('MaximModuleTicketBundle:UserTicket')->findOneBy(array("id" => $id));
                if(!$ticket) {
                    $response['success'] = false;
                    throw $this->createNotFoundException("Could not find ticket with id: " . $id);
                } else {
                    $tr = new TicketReply($ticket, $data['text'], $this->getUser());
                    $th = new TicketHistory($ticket, TicketHistory::TYPE_REPLY, $this->getUser());
                    $em->persist($tr);
                    $em->persist($th);
                    $em->flush();
                }

                $response['success'] = true;

            }else{

                $response['success'] = false;
                $response['cause'] = 'whatever';

            }

            return new JsonResponse( $response );
        }

        $data['ticket'] = $this->getDoctrine()->getRepository('MaximModuleTicketBundle:UserTicket')->findOneBy(array("id" => $id, "user" => $this->getUser()));
        $data['replyform'] = $replyForm->createView();

        return $this->render("Module:Ticket/view.html.twig", $data);
    }

    public function fetchRepliesAction($id)
    {
        $em = $this->getDoctrine();
        $logger = $this->get('logger');

        /**
         * @var UserTicket $ticket
         */
        $ticket = $em->getRepository('MaximModuleTicketBundle:UserTicket')->findOneBy(array("id" => $id));

        if(!$ticket) {
            $logger->error("could not find blabla");
            throw $this->createNotFoundException("Could not find ticket with id:" . $id);
        }


        $logger->error($id);
        if($this->getUser() == null || $ticket->getUser() != $this->getUser()) {
            throw new AccessDeniedException("You are not allowed to access the ticket with id: " . $id);
        }

        $data['replies'] = $ticket->getReplies();

        return $this->render('Module:Ticket/Ajax/replies.html.twig', $data);
    }

    public function closeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('_ticketid');

        $ticket = $em->getRepository('MaximModuleTicketBundle:UserTicket')->findOneBy(array("id" => $id, "user" => $this->getUser()));

        $ticket->setClosed(true);
        $th = new TicketHistory($ticket, TicketHistory::TYPE_CLOSED, $this->getUser());
        $em->persist($th);

        $em->flush();

        return $this->redirect($this->generateUrl("ticket_view", array("id" => $ticket->getId(), "name" => $ticket->getTicket()->getName())));
    }

    public function openAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->request->get('_ticketid');

        $ticket = $em->getRepository('MaximModuleTicketBundle:UserTicket')->findOneBy(array("id" => $id, "user" => $this->getUser()));

        $ticket->setClosed(false);

        $th = new TicketHistory($ticket, TicketHistory::TYPE_OPENED, $this->getUser());
        $em->persist($th);

        $em->flush();

        return $this->redirect($this->generateUrl("ticket_view", array("id" => $ticket->getId(), "name" => $ticket->getTicket()->getName())));
    }
} 