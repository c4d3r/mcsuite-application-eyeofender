<?php
/**
 * Author: Maxim
 * Date: 15/11/13
 * Time: 10:20
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WidgetController extends Controller
{
    public function latestThreadsAction($amount = 10)
    {
        $em = $this->getDoctrine()->getManager();

        # get latest threads
        $threads = $em->getRepository('MaximModuleForumBundle:Thread')->findLatestThreads(10, $this->container->getParameter('website'));

        $data['threads'] = $threads;

        return $this->render('MaximCMSBundle:Forum:widget/latestThreads.html.twig', $data);
    }
}