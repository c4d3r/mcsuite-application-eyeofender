<?php
/**
 * Project: MCSuite
 * File: StatisticController.php
 * User: Maxim
 * Date: 03/03/13
 * Time: 12:20
 */

namespace Maxim\CMSBundle\Controller;

use Maxim\CMSBundle\Entity\Visitor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Common\Cache\ApcCache;
class StatisticController extends Controller{

    public function indexAction()
    {
        //STATISTICS
        $stats = $this->get('statistic.helper');
        $data['stat'] = $stats->getVisitorsOnline();

        $response =  $this->render('MaximCMSBundle:widgets:statistics.html.twig', $data);

        return $response;
    }
    public function visitorAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();

        $ip = $_SERVER['REMOTE_ADDR'];
        $time = time();

        $session = $this->getRequest()->getSession();
        $session_visitor = $session->get('visitor');
        $session_visitor_day = $session->get('visitor_day');

        if(!isset($session_visitor_day))
        {
            $session->set("visitor_day", $time);
        }
        else if(!isset($session_visitor))
        {
            // insert him into the database and create a session
            $this->createVisitor($ip, $time);
            $session->set("visitor", $time);
        }
        else
        {
            //Check for daily stats first
            if(!($session_visitor > (time() - (86400 * cal_days_in_month(CAL_GREGORIAN, date("m"), date("Y"))))))
            {
                $session->set("visitor_day", $time);
            }
            if(!($session_visitor > (time() - 600)))
            {
                $this->createVisitor($ip, $time);
                $session->set("visitor", $time);
            }
        }
        $session->save();
        session_write_close();
        return new Response(json_encode(array("success" => true)));
    }
    public function createVisitor($ip, $time)
    {

        $security = $this->get("security.context");

        $visitor = new Visitor();
        $visitor->setIp($ip);
        $visitor->setTime($time);
        $visitor->setUserAgent($_SERVER['HTTP_USER_AGENT']);

        // Check if user is logged in
        if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            $user = $security->getToken()->getUser();
            $visitor->setUser($user);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($visitor);
        $em->flush();
    }
}