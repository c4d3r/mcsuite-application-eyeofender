<?php
/**
 * Project: MCSuite
 * File: AdminGroupController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 22:21
 */

namespace Maxim\AdminBundle\Controller;
use Doctrine\ORM\EntityNotFoundException;
use Maxim\CMSBundle\Entity\Group;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;

class AdminGroupController extends Controller{

    public function listViewAction()
    {
        return $this->render('MaximAdminBundle:Groups:view.html.twig');
    }

    public function addViewAction()
    {
        return $this->render('MaximAdminBundle:Groups:add.html.twig');
    }

    public function editAction()
    {
        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            $em = $this->getDoctrine()->getManager();

            $group = $em->getRepository('MaximCMSBundle:Group')->findOneBy(array("id" => $request->request->get('_admin_group_id')));
            if(!$group){ throw new EntityNotFoundException(); }

            $group->setRoleName($request->request->get('_admin_group_role'));
            $group->setName($request->request->get('_admin_group_name'));
            $group->setDescription($request->request->get('_admin_group_description'));
            $group->setApplication($request->request->get('_admin_group_application'));

            $em->flush();

            $result = array("success" => true, "message" => "Your group has been edited successfully");
        }
        else
        {
            $result = array("success" => false, "message" => "Not a POST request");
        }

        return new Response(json_encode($result));
    }

    public function viewAction($id)
    {
        $group = $this->getDoctrine()->getRepository('MaximCMSBundle:Group')->findOneBy(array("id" => $id));
        if(!$group){ throw new EntityNotFoundException(); }
        $data['group'] = $group;
        return $this->render('MaximAdminBundle:Groups:edit.html.twig', $data);
    }
    public function listAction() {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Group');

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

            $name = $request->request->get('_admin_group_name');
            $role = $request->request->get('_admin_group_role');
            $applicable = $request->request->get('_admin_group_application');
            $description = $request->request->get('_admin_group_description');

            $group = new Group($name, $role);
            $group->setDescription($description);
            $group->setApplication($applicable);

            $em->persist($group);
            $em->flush();

            $result = array("success" => true, "message" => "Your group has been added successfully");
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

            $group = $em->getRepository('MaximCMSBundle:Group')->findOneBy(array("id" => $id));
            if(!$group){ throw new EntityNotFoundException(); array("success" => false, "message" => "We could not find the specified group"); }

            $em->remove($group);
            $em->flush();

            $result = array("success" => true, "message" => "Group deleted succesfuly");
        }
        else
        {
            $result = array("success" => false, "message" => "Not a POST request");
        }
        return new Response(json_encode($result));
    }
}