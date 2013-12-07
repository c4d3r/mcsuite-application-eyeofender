<?php
/**
 * Project: MCSuite
 * File: AdminController.php
 * User: Maxim
 * Date: 07/04/13
 * Time: 14:37
 */

namespace Maxim\Module\TicketBundle\Controller;

use Maxim\Module\TicketBundle\Entity\TicketSection;
use Maxim\Module\TicketBundle\Controller\ModuleController;
use Symfony\Component\HttpFoundation\Response;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller{

    public function viewAction()
    {
        return $this->render("MaximModuleTicketBundle:Admin:main.html.twig");
    }
    public function addViewAction()
    {
        $em = $this->getDoctrine()->getManager();

        //Get ranks
        $repository    = $em->getRepository('MaximCMSBundle:Rank');
        $data['ranks'] = $repository->findAll();

        return $this->render("MaximModuleTicketBundle:Admin:Section/add.html.twig", $data);
    }
    public function editViewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        //Get ranks
        $repository = $em->getRepository('MaximCMSBundle:Rank');

        $data['ranks']		= $repository->findAll();
        $data['section'] = $this->getDoctrine()->getRepository('MaximModuleTicketBundle:TicketSection')->findOneBy(array("id" => $id));
        return $this->render("MaximModuleTicketBundle:Admin:Section/edit.html.twig", $data);
    }
    public function ajaxAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximModuleTicketBundle:Ticket');

        // Add the $datatable variable, or other needed variables, to the callback scope
        $datatable->addWhereBuilderCallback(function($qb) use ($datatable) {
            $andExpr = $qb->expr()->andX();

            // The entity is always referred to using the CamelCase of its table name
            $andExpr->add($qb->expr()->eq('Ticket.closed','0'));

            // Important to use 'andWhere' here...
            $qb->andWhere($andExpr);

        });
        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }
    public function sectionAjaxAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximModuleTicketBundle:TicketSection');
        $datatable->setDefaultJoinType(Datatable::JOIN_INNER);
        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }

    public function viewTicketAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $data['ticket']   = $em->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $id));
        $data['replies']  = $em->getRepository('MaximModuleTicketBundle:TicketReply')->findBy(array("ticket" => $data['ticket']));
        $data['sections'] = $em->getRepository('MaximModuleTicketBundle:TicketSection')->findAll();
        return $this->render("MaximModuleTicketBundle:Admin:view.html.twig", $data);
    }

    public function closeAction($id = null)
    {

        $request = $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            $id = $request->request->get('_ticket');
        }
        if($id != null)
        {
            $em     = $this->getDoctrine()->getManager();
            $ticket = $em->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $id));
            $ticket->setClosed(1);
            $ticket->setStatus("The ticket has been closed by a moderator");
            $em->flush();
            $output = array("success" => true, "message" => "The ticket has been closed");
        }
        else
        {
            $output = array("success" => false, "message" => "Invalid close request");
        }
        return new Response(json_encode($output));
    }

    public function changeSection()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            $em     = $this->getDoctrine()->getManager();

            $ticketid  = $request->request->get('_ticket');
            $sectionid = $request->request->get('_section');

            $ticket = $em->getRepository('MaximModuleTicketBundle:Ticket')->findOneBy(array("id" => $ticketid));
            $section = $em->getRepository('MaximModuleTicketBundle:TicketSection')->findOneBy(array("id" => $sectionid));

            $ticket->setSection($section);
            $em->flush();
            $output = array("success" => true, "message" => "The ticket's section has been closed");
        }
        else
        {
            $output = array("success" => false, "message" => "Invalid section change request");
        }
        return new Response(json_encode($output));
    }


    # SECTIONS
    public function sectionAddAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            $name  = $request->request->get('_name');
            $ranks = $request->request->get('_ranks');

            $section = new TicketSection();

            $repoRank = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank');
            //add updated list
            foreach($ranks as $rank)
            {
                $rankadd = $repoRank->findOneById($rank);
                $section->addRank($rankadd);
            }

            $section->setName($name);
            $section->setCreatedby($this->getUser());
            $section->setCreatedon(new \DateTime("now"));

            $em = $this->getDoctrine()->getManager();
            $em->persist($section);
            $em->flush();
            $output = array("success" => true, "message" => "The new section has been added succesfully");
        }
        else
        {
            $output = array("success" => false, "message" => "Invalid section change request");
        }
        return new Response(json_encode($output));
    }

    public function sectionEditAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $sectionid =$request->request->get('_section');
            $section = $em->getRepository('MaximModuleTicketBundle:TicketSection')->findOneBy(array("id" => $sectionid));
            $name = $request->request->get('_name');
            $ranks = $request->request->get('_ranks');

            //Clear all ranks, just read them afterwards, way faster
            //get current ranks
            $currRanks = $section->getRank();
            foreach($currRanks as $rank)
            {
                $section->removeRank($rank);
            }

            $repoRank = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank');
            //add updated list
            foreach($ranks as $rank)
            {
                $rankadd = $repoRank->findOneById($rank);
                $section->addRank($rankadd);
            }

            $section->setName($name);

            $em->flush();
            $output = array("success" => true, "message" => "The section has been edited succesfully");
        }
        else
        {
            $output = array("success" => false, "message" => "Invalid section change request");
        }
        return new Response(json_encode($output));
    }

    public function sectionDeleteAction($id)
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $section = $em->getRepository('MaximModuleTicketBundle:TicketSection')->findOneBy(array("id" => $id));

            $em->remove($section);
            $em->flush();

            $output = array("success" => true, "message" => "The section has been deleted succesfully");
        }
        else
        {
            $output = array("success" => false, "message" => "Invalid section change request");
        }
        return new Response(json_encode($output));
    }

    public function replyEditViewAction($ticketid, $id) {
        $data['ticket'] = array("id" => $ticketid);
        $data["reply"] = $this->getDoctrine()->getRepository('MaximModuleTicketBundle:TicketReply')->findOneBy(array("id" => $id));
        return $this->render('MaximModuleTicketBundle:Admin:edit_reply.html.twig', $data);
    }
    public function replyEditAction($id) {

        $request = $this->getRequest();

        $text = $request->request->get('_reply');

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $reply = $em->getRepository('MaximModuleTicketBundle:TicketReply')->findOneBy(array("id" => $id));

            if($reply) {
                $reply->setText($text);
                $em->flush();
                return new Response(json_encode(array("success" => true, "message" => "Reply has been edited!")));
            } else {
                return new Response(json_encode(array("success" => false, "message" => "Could not find reply")));
            }

        }

    }

    public function replyDeleteAction($id) {

        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();
            $reply = $em->getRepository('MaximModuleTicketBundle:TicketReply')->findOneBy(array("id" => $id));

            if($reply) {
                $em->remove($reply);
                $em->flush();
                return new Response(json_encode(array("success" => true, "message" => "Reply has been deleted!")));
            } else {
                return new Response(json_encode(array("success" => false, "message" => "Could not find reply")));
            }

        }

    }
}