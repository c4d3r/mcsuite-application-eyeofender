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
            "SELECT c, w, f
            FROM MaximModuleForumBundle:Category c
            LEFT JOIN c.forums f
            INNER JOIN c.website w
            WHERE w.id = :wid
            ORDER BY c.sort DESC, f.sort DESC, c.title ASC, f.title ASC"
        )->setParameter("wid", $this->container->getParameter('website'));
        $query->useResultCache(true, 3600, __METHOD__ . serialize($query->getParameters()));
        $data['categories'] = $query->getResult();
        $data['config'] = array("forum" => array("popularPERC" => 20));
        # CLEANUP
        $query = null;

        #RENDER
        return $this->render('MaximCMSBundle:Forum:main.html.twig', $data);
    }
    public function forumViewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');
        $request = Request::createFromGlobals();

        # FORUM
        $forum = $em->getRepository('MaximModuleForumBundle:Forum')->findOneBy(array("id" => $id));

        # THREADS
        #   PINNED THREADS
        $query = $em->createQuery(
            "SELECT t, u, f
            FROM MaximModuleForumBundle:Thread t
            LEFT JOIN t.createdBy u
            JOIN t.forum f
            WHERE f.id = :id AND t.pinned = true
            ORDER BY t.createdOn DESC"
        )
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->setParameter('id', $id);
        $query->useResultCache(true, 10, __METHOD__ . serialize($query->getParameters()));

        $result = $query->getResult();
        if(count($result) > 0) {
            $data['threads_pinned'] = $result;
        }

        #   NON-PINNED THREADS
        $query = $em->createQuery(
            "SELECT t, u, f
            FROM MaximModuleForumBundle:Thread t
            LEFT JOIN t.createdBy u
            JOIN t.forum f
            WHERE f.id = :id AND t.pinned = false
            ORDER BY t.createdOn DESC"
        )
            ->setParameter('id', $id)
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);
        $query->useResultCache(true, 10, __METHOD__ . serialize($query->getParameters()));

        $paginator  = $this->get('knp_paginator');
        $threads = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1)/*page number*/,
            20/*limit per page*/
        );

        $page = $request->query->get('page');

        if(!($page == null))  {
            if(!($page == 1)) {
                $data['page'] = true;
            }
        }

        $data['forum']   = $forum;
        $data['threads'] = $threads;
        # CLEANUP
        $query = null;

        #RENDER
        return $this->render('MaximCMSBundle:Forum:viewForum.html.twig', $data);
    }
}