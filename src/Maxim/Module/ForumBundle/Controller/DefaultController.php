<?php
namespace Maxim\Module\ForumBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{

    public function loadAction()
    {
        $em = $this->getDoctrine()->getManager();

        # LOAD CATEGORIES
        $query = $em->createQuery(
            "SELECT c, w, f, p, t, u
            FROM MaximModuleForumBundle:Category c
            LEFT JOIN c.forums f
            INNER JOIN c.website w
            LEFT JOIN f.lastPostCreator u
            LEFT JOIN f.lastPost p
            LEFT JOIN p.thread t
            WHERE w.id = :wid
            ORDER BY c.sort DESC, f.sort DESC, c.title ASC, f.title ASC"
        )->setParameter("wid", $this->container->getParameter('website'));
        $query->useResultCache(true, 60, __METHOD__ . serialize($query->getParameters()));
        $data['categories'] = $query->getResult();
        $data['config'] = array("forum" => array("popularPERC" => 20));

        # CLEANUP
        $query = null;

        #RENDER
        return $this->render('MaximCMSBundle:Forum:main.html.twig', $data);
    }
    public function forumViewAction($id, $seo)
    {
        $em = $this->getDoctrine()->getManager();
        $request = Request::createFromGlobals();

        # forum
        $forum = $em->getRepository('MaximModuleForumBundle:Forum')->findOneBy(array("id" => $id));

        # threads
        $data['threads_pinned'] = $em->getRepository('MaximModuleForumBundle:Thread')->findThreads($id, true);
        $threads = $em->getRepository('MaximModuleForumBundle:Thread')->findThreads($id, false);

        $logger = $this->get('logger');

        $paginator  = $this->get('knp_paginator');

        $threadsPaginator = $paginator->paginate(
            $threads,
            $this->get('request')->query->get('page', 1)/*page number*/,
            20/*limit per page*/
        );

        $page = $request->query->get('page');

        if(!($page == null))  {
            if(!($page == 1)) {
                $data['page'] = true;
            }
        }

        $data['threads'] = $threadsPaginator;
        $data['forum']   = $forum;
        # CLEANUP
        $query = null;

        #RENDER
        return $this->render('MaximCMSBundle:Forum:viewForum.html.twig', $data);
    }
}