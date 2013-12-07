<?php
namespace Maxim\Module\ForumBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller{

    public function loadAction()
    {
        $em = $this->getDoctrine()->getManager();

        # LOAD FORUMS
        $query = $em->createQuery(
            "SELECT f, c, u, t
            FROM MaximModuleForumBundle:Forum f
            LEFT JOIN f.createdBy u
            LEFT JOIN f.category c
            LEFT JOIN f.threads t
            ORDER BY c.sort DESC"
        );
        $query->useResultCache(true, 3600, __METHOD__ . serialize($query->getParameters()));
        $data['forums'] = $query->getResult();

        # LOAD CATEGORIES
        $query = $em->createQuery(
            "SELECT c, w
            FROM MaximModuleForumBundle:Category c
            JOIN c.website w
            WHERE w.id = :wid
            ORDER BY c.sort DESC"
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
        $request = $this->getRequest();

        # FORUM
        $forum = $em->getRepository('MaximModuleForumBundle:Forum')->findOneBy(array("id" => $id));

        # THREADS
        #   PINNED THREADS
        $query = $em->createQuery(
            "SELECT t, u, f, p, u2
            FROM MaximModuleForumBundle:Thread t
            JOIN t.posts p
            JOIN p.createdBy u2
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
            "SELECT t, u, f, p, u2
            FROM MaximModuleForumBundle:Thread t
            LEFT JOIN t.posts p
            LEFT JOIN p.createdBy u2
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