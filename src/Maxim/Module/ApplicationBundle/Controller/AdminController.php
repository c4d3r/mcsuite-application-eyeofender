<?php
/**
 * Project: MCSuite
 * File: AdminController.php
 * User: Maxim
 * Date: 20/06/13
 * Time: 21:50
 */

namespace Maxim\Module\ApplicationBundle\Controller;
use Maxim\Module\ApplicationBundle\Entity\Application;
use Maxim\Module\ApplicationBundle\Entity\ApplicationReply;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Maxim\CMSBundle\Controller\ModuleController;

class AdminController extends Controller
{
    public function applicationAction()
    {
        return $this->render('MaximModuleApplicationBundle:Admin:application.html.twig');
    }
    public function applicationAddViewAction()
    {
        $websites = $this->getDoctrine()->getRepository('MaximCMSBundle:Website')->findAll();
        $ranks = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank')->findAll();

        $data['websites'] = $websites;
        $data['ranks'] = $ranks;
        return $this->render("MaximModuleApplicationBundle:Admin:add_form.html.twig", $data);
    }
    public function applicationCreateAction()
    {
        $websites = $this->getDoctrine()->getRepository('MaximCMSBundle:Website')->findAll();
        $ranks = $this->getDoctrine()->getRepository('MaximCMSBundle:Rank')->findAll();

        $data['websites'] = $websites;
        $data['ranks'] = $ranks;
        return $this->render('MaximModuleApplicationBundle:Admin:createApplication.html.twig', $data);
    }

    public function applicationDenyAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximModuleApplicationBundle:UserApplication');
                $application = $repository->findOneBy(array('id' => $request->request->get('_admin_app_id')));

                if($application)
                {
                    $em = $this->getDoctrine()->getManager();
                    $application->setDenied(1);
                    $em->flush();
                    $result = array("success" => true, "message" => "Application denied successfully");
                }
                else
                {
                    $result = array("success" => false, "message" => "Error denying, application with id:".$request->request->get('_admin_app_id')." could not be found");
                }
            }
            catch(\Exception $ex)
            {
                $result  = array("success" => false, "message" => "Error denying application: ".$ex->getMessage());
            }
            return new Response(json_encode($result));
        }
    }
    public function applicationAcceptAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximModuleApplicationBundle:UserApplication');
                $application = $repository->findOneBy(array('id' => $request->request->get('_admin_app_id')));

                if($application)
                {
                    $em = $this->getDoctrine()->getManager();
                    $application->setDenied(2);
                    $em->flush();
                    $result = array("success" => true, "message" => "Application accepted successfully");
                }
                else
                {
                    $result = array("success" => false, "message" => "Error accepting, application with id:".$request->request->get('_admin_app_id')." could not be found");
                }
            }
            catch(\Exception $ex)
            {
                $result  = array("success" => false, "message" => "Error accepting application: ".$ex->getMessage());
            }
            return new Response(json_encode($result));
        }
    }
    public function applicationListAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximCMSBundle:UserApplication');

        // Add the $datatable variable, or other needed variables, to the callback scope
        $datatable->addWhereBuilderCallback(function($qb) use ($datatable) {
            $andExpr = $qb->expr()->andX();

            // The entity is always referred to using the CamelCase of its table name
            $andExpr->add($qb->expr()->eq('UserApplication.denied','0'));

            // Important to use 'andWhere' here...
            $qb->andWhere($andExpr);

        });

        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));

    }
    public function applicationAcceptedListAction() {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximModuleApplicationBundle:UserApplication');

        // Add the $datatable variable, or other needed variables, to the callback scope
        $datatable->addWhereBuilderCallback(function($qb) use ($datatable) {
            $andExpr = $qb->expr()->andX();

            // The entity is always referred to using the CamelCase of its table name
            $andExpr->add($qb->expr()->eq('UserApplication.denied','2'));

            // Important to use 'andWhere' here...
            $qb->andWhere($andExpr);

        });

        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));
    }
    public function applicationAddAction()
    {
        $em = $this->getDoctrine()->getManager();

        $request    = $this->getRequest();

        $tempFields = $request->request->get('_applicationform');
        $logger     = $this->get('logger');

        # set keys
        $fields = array();
        $organized = array();
        $main = array();

        $temp = null;
        $type = null;
        foreach($tempFields as $field)
        {
            if(strpos($field['name'], "field") !== false)
            {

                $temp = $field['name'];
                $type = (strpos($field['name'], "fieldtype") !== false) ? Application::FIELD_TYPE : Application::FIELD_NAME;

                $fields[(int)$temp[strlen($temp) - 1]][$type] = $field['value'];
            }
            else
            {
                $main[$field['name']] = $field['value'];
            }
        }


        $name       = $main['_admin_app_name'];
        $website = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $main['_admin_app_website']));
        $rank    = $em->getRepository('MaximCMSBundle:Rank')->findOneBy(array("id" => $main['_admin_app_rank']));

        $application = new Application();
        $application->setWebsite($website);
        $application->setEnabled(true);
        $application->setRank($rank);
        $application->setName($name);
        $application->setFields($fields);

        $em->persist($application);
        $em->flush();


        return new Response(json_encode(array("success" => true, "message" => "Application has been added succesful")));
    }

    public function viewApplicationAction($id)
    {
        //Get his info
        $em 		 = $this->getDoctrine()->getManager();
        $application  = $em->getRepository('MaximModuleApplicationBundle:UserApplication')->findOneBy(array("id" => $id));


        if($application)
        {
            $data['application'] = $application;

            $dateofbirth = $application->getUser()->getDateOfBirth();

            $data['age'] = ModuleController::calcAge($dateofbirth);

            //Get this user his minecraft accounts
           // $repository = $em->getRepository('MaximCMSBundle:Useraccounts');
           // $accounts	= $repository->findBy(array("id" => $application->getUser()->getId()));

            //$data['accounts'] = $accounts;

            $data['replies']  = $em->getRepository('MaximModuleApplicationBundle:ApplicationReply')->findBy(array("application" => $data['application']));

            return $this->render("MaximModuleApplicationBundle:Admin:view.html.twig", $data);
        }
        else
        {
            throw $this->createNotFoundException("We were unable to find the application with id: " . $id);
        }
    }

    public function applicationReplyAction()
    {
        $request = $this->getRequest();
        $logger = $this->get('logger');

        if($request->isXmlHttpRequest())
        {
            try
            {
                $em = $this->getDoctrine()->getManager();
                $bbcode = $this->get('bbcode.helper');
                $text       = $request->request->get('_reply');
                $user       = $this->get('security.context')->getToken()->getUser();
                $applicationid   = $request->request->get('_application');
                $application     = $em->getRepository('MaximModuleApplicationBundle:UserApplication')->findOneBy(array("id" => $applicationid));

                $reply = new ApplicationReply();
                $reply->setText($bbcode->parse($text));
                $reply->setUser($user);
                $reply->setApplication($application);

                //$application->setStatus("Waiting from a response from the ticket owner");
                $em->persist($reply);
                $em->flush();

                $output = array("success" => true, "message" => "Your reply has been added");

            }
            catch(\Exception $ex)
            {
                $logger->err("APPLICATION: " . $ex->getMessage());
                $output = array("success" => false, "message" => "An error has occured while adding your reply, please try again later");
            }
            return new Response(json_encode($output));
        }
    }
}