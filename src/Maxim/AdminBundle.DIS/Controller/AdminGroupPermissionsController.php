<?php
/**
 * Project: MCSuite
 * File: adminGroupPermissionsController.php
 * User: Maxim
 * Date: 28/04/13
 * Time: 10:46
 */

namespace Maxim\AdminBundle\Controller;
use Doctrine\ORM\EntityNotFoundException;
use Maxim\CMSBundle\Entity\PermissionGroup;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;

class AdminGroupPermissionsController extends Controller
{
    public function editAction($id)
    {
        $em = $this->getDoctrine();

        $grantedapps = array();
        $applications_available = array();
        $permissions = array();
        $rank = $em->getRepository('MaximCMSBundle:Rank')->findOneBy(array("id" => $id));
        if(!$rank){ throw new EntityNotFoundException(); }

        $applications = $em->getRepository('MaximCMSBundle:CoreApplication')->findAll();
        $grantedapps  = $em->getRepository('MaximCMSBundle:Rank')->findOneBy(array("id" => $id));

        $permissions = $rank->getApplications();

        if($grantedapps && (count($grantedapps) > 0))
        {
            $apps = $grantedapps->getApplications();

            foreach($applications as $application)
            {
                $found = false;
                foreach($apps as $app)
                {
                    if($app->getId() == $application->getId())
                    {
                        $found = true;
                    }
                }
                if(!$found)
                {
                    $applications_available[] = $application;
                }
            }
        }

        $data['rank'] = $rank;
        $data['permissions'] = $permissions;
        $data['applications'] = $applications_available;
        return $this->render('MaximAdminBundle:Ranks/permissions/group:edit.html.twig', $data);
    }
    public function listAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:Rank');

        // The default type for all joins is inner. Change it to left if desired.
        $datatable->setDefaultJoinType(Datatable::JOIN_LEFT);

        return $datatable->getSearchResults();
    }
    public function saveAction()
    {
        $request = $this->getRequest();
        if($request->isXmlHttpRequest())
        {
            $logger = $this->get('logger');
            $em = $this->getDoctrine()->getManager();
            $rank = $em->getRepository('MaximCMSBundle:Rank')->findOneBy(array("id" => $request->request->get('_admin_rank')));
            if(!$rank){return new Response(json_encode(array("success" => false, "message" => "Could not find the rank specified"))); }

            $permissions = $request->request->get('_admin_permissions');

            foreach($rank->getApplications() as $a)
            {
                $rank->removeApplication($a);
            }

            $em->flush();
            try
            {

                if(!$request->request->get('_admin_permissions') == null)
                {
                    foreach($permissions as $permission)
                    {
                        $app = $em->getRepository('MaximCMSBundle:CoreApplication')->findOneBy(array("id" => $permission));
                        $rank->addApplication($app);
                    }
                    $em->flush();
                }
                $output = array("success" => true, "message" => "Your permissions have been updated");
            }
            catch(\Exception $ex)
            {
                $this->get('logger')->err("PERMISSIONS: " . $ex->getMessage());
                $output = array("success" => false, "message" =>  "An error occured, please try again later");
            }
        }
        else
        {
            $output = array("success" => false, "message" => "not a post request");
        }
        return new Response(json_encode($output));
    }
}