<?php
/**
 * Project: MCSuite
 * File: ApplicationController.php
 * User: Maxim
 * Date: 10/03/13
 * Time: 10:26
 */

namespace Maxim\Module\ApplicationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Maxim\Module\ApplicationBundle\Entity\Application;
use Maxim\Module\ApplicationBundle\Entity\UserApplication;
use Maxim\Module\ApplicationBundle\Entity\ApplicationReply;

class ApplicationController extends Controller{

    /**
     * Defines what application to load from a file
     */
    public function viewAction($id, $name)
    {
        #init vars
        $em = $this->getDoctrine()->getManager();
        $request = $this->getRequest();

        #action
        $application = $em->getRepository('MaximModuleApplicationBundle:Application')->findOneBy(array("id" => $id));
        if(!$application)
            throw $this->createNotFoundException("We could not find the requested application form");

        $formBuilder = $this->createFormBuilder();

        $fields = $application->getFields();
        foreach($fields as $key => $field)
        {
            $formBuilder->add($key, ($field[Application::FIELD_TYPE] == "textfield" ? "text" : "textarea"), array('label' => $field[Application::FIELD_NAME]));
        }

        # regular actions
        $formBuilder->add('save', 'submit');
        $form = $formBuilder->getForm();

        # handle action
        $form->handleRequest($request);

        if ($form->isValid()) {

            $data = $form->getData();

            # build details
            $details = array();
            foreach($data as $key => $detail)
            {
                $details[$fields[$key][Application::FIELD_NAME]] = $detail;
            }

            $ua = new UserApplication();
            $ua->setApplication($application);
            $ua->setUser($this->getUser());
            $ua->setDetails($details);

            $em->persist($ua);
            $em->flush();

            $this->get('session')->getFlashBag()->set('notice', 'Your application has been submitted');

            return $this->redirect($this->generateUrl("application_account"));
        }

        return $this->render("MaximModuleApplicationBundle:Application:view.html.twig", array(
            'form' => $form->createView(),
        ));
    }
    public function listAction()
    {
        # init vars
        $em = $this->getDoctrine()->getManager();
        $website = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $this->container->getParameter("website")));

        # make data
        $data['applications'] = $em->getRepository('MaximModuleApplicationBundle:Application')->findBy(array("website" => $website));

        # return view
        return $this->render("MaximModuleApplicationBundle:Application:applications.html.twig", $data);
    }
    public function accountAction()
    {
        # init data
        $data['userApplications'] = $this->getDoctrine()->getRepository('MaximModuleApplicationBundle:UserApplication')->findBy(array("user" => $this->getUser()));

        # return view
        return $this->render("MaximModuleApplicationBundle:Application:applications_user.html.twig", $data);
    }
    public function accountViewAction($id, $name)
    {
        $logger = $this->get('logger');
        $em          = $this->getDoctrine();
        $application = $em->getRepository('MaximModuleApplicationBundle:UserApplication')->findOneBy(array("user" => $this->getUser(), "id" => $id));

        if(!$application)
            throw $this->createNotFoundException("We could not find the requested application form");

        # instantiate replies
        $replies = $em->getRepository('MaximModuleApplicationBundle:ApplicationReply')->findBy(array("application" => $application));

        # fill data
        $data['replies']  = $replies;
        $data['application'] = $application;

        return $this->render("MaximModuleApplicationBundle:Application:application_detail.html.twig", $data);
    }
    public function replyAction()
    {
        $logger = $this->get('logger');
        $request = $this->getRequest();

        if($request->isXmlHttpRequest())
        {
            try
            {
                $em = $this->getDoctrine()->getManager();

                $text          = $request->request->get('_reply');
                $user          = $this->get('security.context')->getToken()->getUser();
                $applicationid = $request->request->get('_application');
                $application   = $em->getRepository('MaximModuleApplicationBundle:UserApplication')->findOneBy(array("id" => $applicationid));

                if($user)
                {
                    if($this->isApplicationOwner($application, $user))
                    {
                        $reply = new ApplicationReply();
                        $reply->setText($text);
                        $reply->setUser($user);
                        $reply->setApplication($application);

                        $em->persist($reply);
                        $em->flush();

                        $output = array("success" => true, "message" => "Your reply has been added");
                    }
                    else
                    {
                        $output = array("success" => false, "message" => "This is not your application");
                    }
                }
                else
                {
                    $output = array("success" => false, "message" => "We were unable to find the user account");
                }
            }
            catch(\Exception $ex)
            {
                $logger->err("APPLICATION: " . $ex->getMessage());
                $output = array("success" => false, "message" => "An error has occured while adding your reply, please try again later");
            }
            return new Response(json_encode($output));
        }
    }
    public function isApplicationOwner($application, $user)
    {
        return ($application->getUser()->getId() == $user->getId());
    }
    public function addAction(){

        $request = $this->getRequest();
        $logger = $this->get('logger');

        if($request->isXmlHttpRequest())
        {
            $labels  = explode(';', $request->request->get("_labels"));
            $fields  = explode(';', $request->request->get("_fields"));
            $app_id  = $request->request->get("_application_id");

            //get the rank
            $em    = $this->getDoctrine()->getManager();
            $app   = $em->getRepository('MaximModuleApplicationBundle:Application')->findOneBy(array("id" => $app_id));

            $query = $em->createQuery(
                'SELECT a FROM MaximModuleApplicationBundle:UserApplication a WHERE a.date > :time AND a.user = :user ORDER BY a.date DESC'
            )
                ->setParameter('time', date("Y-m-d H:i:s", (time() - 2592000)))
                ->setParameter('user', $this->getUser());

            $applications = $query->getResult();
            if(count($applications) > 0){ return new Response(json_encode(array("success" => false, "message" => "You have already submited an application in the last 30 days, please try again later."))); }

            if($app)
            {             $logger->err("hi5");
                $description = "<table>";
                foreach($fields as $key => $field)
                {
                    $description .= "<tr><td>" . $labels[$key] . "</td><td>" . $field . "</td></tr>";
                }

                $description .= "</table>";
                $application = new UserApplication();
                $application->setUser($this->get('security.context')->getToken()->getUser());
                $application->setDate(new \DateTime("now"));
                $application->setDescription($description);
                $application->setApplication($app);
                $em->persist($application);
                $em->flush();

                $logger->err("h6i");
                $output = array("success" => true, "message" => "Your application was submited succesfuly");
            }
            else
            {
                $output = array("success" => false, "message" => "Could not find the application that you are applying for");
            }
        }
        else
        {
            $output = array("success" => false, "message" => "Not a valid POST request");
        }
        return new Response(json_encode($output));

    }
}