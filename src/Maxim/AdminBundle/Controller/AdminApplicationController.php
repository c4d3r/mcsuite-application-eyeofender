<?php
/**
 * Project: MCSuite
 * File: AdminApplicationController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 19:34
 */

namespace Maxim\AdminBundle\Controller;
use Maxim\Module\ApplicationBundle\Entity\Application;
use Maxim\Module\ApplicationBundle\Entity\ApplicationReply;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Maxim\CMSBundle\Controller\ModuleController;

class AdminApplicationController extends Controller {

    public function applicationAction()
    {
        return $this->render('MaximCMSBundle:admin:application.html.twig');
    }
    public function applicationAddViewAction()
    {
        return $this->render('MaximCMSBundle:admin:application/add.html.twig');
    }
    public function applicationDenyAction()
    {
        $request = $this->getRequest();
        if ($request->isXmlHttpRequest()) {
            try
            {
                $repository = $this->getDoctrine()->getRepository('MaximModuleApplicationBundle:Application');
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

    public function applicationListAction()
    {
        $datatable = $this->get('lankit_datatables')->getDatatable('MaximModuleApplicationBundle:Application');

        // Add the $datatable variable, or other needed variables, to the callback scope
        $datatable->addWhereBuilderCallback(function($qb) use ($datatable) {
            $andExpr = $qb->expr()->andX();

            // The entity is always referred to using the CamelCase of its table name
            $andExpr->add($qb->expr()->eq('Application.denied','0'));

            // Important to use 'andWhere' here...
            $qb->andWhere($andExpr);

        });

        return new Response($datatable->getSearchResults(Datatable::RESULT_JSON));

    }
    public function applicationAddAction()
    {
        $logger     = $this->get('logger');
        $request    = $this->getRequest();
        $name       = $request->request->get('_admin_app_name');
        str_replace(" ", "-", $name);
        $content = "{% extends 'MaximCMSBundle:pages/application:standard_template.html.twig' %}

                    {% block body %}";
        $content .= $request->request->get('_admin_app_content');
        $content .= "{% endblock %}";


        //$path = '/'.__DIR__.'../Resources/views/themes/eoe/application/' . $name . ".html.twig";
        $path = '../src/Maxim/CMSBundle/Resources/views/themes/eoe/views/pages/application/' . $name . ".html.twig";
        if(strlen($name) == 0){ return new Response(json_encode(array("success" => false, "message" => "You must specify a name for the application"))); }
        if(file_exists($path)){ return new Response(json_encode(array("success" => false, "message" => "Application with the name: $name already exists"))); }

        fopen($path, 'w');
        if(is_writable($path))
        {
            if(!$handle = fopen($path, 'a'))
            {
                $logger->err("FILEHANDLER: Can not open file name $path");
                return new Response(json_encode(array("success" => false, "message" => "Can not open the file")));
            }

            if(fwrite($handle, $content) === FALSE)
            {
                $logger->err("FILEHANDLER: Can not write to file ($path)");
                return new Response(json_encode(array("success" => false, "message" => "Can not write to file")));
            }
            fclose($handle);
        }
        else
        {
            $logger->err("FILEHANDLER: the file is not writable ($path)");
            return new Response(json_encode(array("success" => false, "message" => "The file ($path) is not writable")));
        }

        return new Response(json_encode(array("success" => true, "message" => "Application has been added succesful")));
    }

    public function viewApplicationAction($id)
    {
        //Get his info
        $em 		 = $this->getDoctrine()->getManager();
        $application  = $em->getRepository('MaximModuleApplicationBundle:Application')->findOneBy(array("id" => $id));


        if($application)
        {
            $data['application'] = $application;

            $dateofbirth = $application->getUser()->getDateOfBirth();

            $data['age'] = ModuleController::calcAge($dateofbirth);

            //Get this user his minecraft accounts
            //$repository = $em->getRepository('MaximCMSBundle:Useraccounts');
            //$accounts	= $repository->findBy(array("id" => $application->getUser()->getId()));

           // $data['accounts'] = $accounts;

            $data['replies']  = $em->getRepository('MaximModuleApplicationBundle:ApplicationReply')->findBy(array("application" => $data['application']));

            return $this->render('MaximCMSBundle:admin:application/view.html.twig', $data);
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
                $application     = $em->getRepository('MaximModuleApplicationBundle:Application')->findOneBy(array("id" => $applicationid));

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