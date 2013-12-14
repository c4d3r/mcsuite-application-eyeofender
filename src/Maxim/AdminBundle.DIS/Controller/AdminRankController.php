<?php
/**
 * Project: MCSuite
 * File: AdminRankController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 22:21
 */

namespace Maxim\AdminBundle\Controller;
use Doctrine\ORM\EntityNotFoundException;
use Maxim\CMSBundle\Entity\Rank;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;

class AdminRankController extends Controller{

    public function listViewAction()
    {
        return $this->render('MaximAdminBundle:Ranks:view.html.twig');
    }

    public function addViewAction()
    {
        return $this->render('MaximAdminBundle:Ranks:add.html.twig');
    }

    public function editAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $rank = $em->getRepository('MaximCMSBundle:Rank')->findOneBy(array("id" => $request->request->get('_admin_rank_id')));
            if(!$rank){ throw new EntityNotFoundException(); }

            $rank->setRoleName($request->request->get('_admin_rank_role'));
            $rank->setName($request->request->get('_admin_rank_name'));
            $rank->setDescription($request->request->get('_admin_rank_description'));
            $rank->setApplication($request->request->get('_admin_rank_application'));

            $em->flush();

            $result = array("success" => true, "message" => "Your rank has been edited successfully");
        }
        else
        {
            $result = array("success" => false, "message" => "Not a POST request");
        }

        return new Response(json_encode($result));
    }

    public function viewAction($id)
    {
        $rank = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank')->findOneBy(array("id" => $id));
        if(!$rank){ throw new EntityNotFoundException(); }
        $data['rank'] = $rank;
        return $this->render('MaximAdminBundle:Ranks:edit.html.twig', $data);
    }
    public function listAction() {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Rank');

        // The default type for all joins is inner. Change it to left if desired.
        $datatable->setDefaultJoinType(Datatable::JOIN_LEFT);

        return $datatable->getSearchResults();
    }
    public function addAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $name = $request->request->get('_admin_rank_name');
            $role = $request->request->get('_admin_rank_role');
            $applicable = $request->request->get('_admin_rank_application');
            $description = $request->request->get('_admin_rank_description');

            $rank = new Rank($name, $role);
            $rank->setDescription($description);
            $rank->setApplication($applicable);

            $em->persist($rank);
            $em->flush();

            $result = array("success" => true, "message" => "Your rank has been added successfully");
        }
        else
        {
            $result = array("success" => false, "message" => "Not a POST request");
        }

        return new Response(json_encode($result));
    }
    public function deleteAction($id)
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $rank = $em->getRepository('MaximCMSBundle:Rank')->findOneBy(array("id" => $id));
            if(!$rank){ throw new EntityNotFoundException(); array("success" => false, "message" => "We could not find the specified rank"); }

            $em->remove($rank);
            $em->flush();

            $result = array("success" => true, "message" => "Rank deleted succesfuly");
        }
        else
        {
            $result = array("success" => false, "message" => "Not a POST request");
        }
        return new Response(json_encode($result));
    }
}