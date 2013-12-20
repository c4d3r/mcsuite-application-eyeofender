<?php

namespace Maxim\CMSBundle\Controller;

use Doctrine\ORM\Query;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Doctrine\Common\Cache\ApcCache;
class DefaultController extends Controller
{
    private $customMenu = array();

    public function indexAction()
    {
        $response =  $this->newsAction();

        return $response;
    }
    public function newsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->get('request');

        $threads = $em->getRepository("MaximModuleForumBundle:Thread")->findNewsPosts($this->container->getParameter('website'));

        $paginator  = $this->get('knp_paginator');
        $threads = $paginator->paginate(
            $threads,
            $this->get('request')->query->get('page', 1)/*page number*/,
            5/*limit per page*/
        );

        $page = $request->query->get('page');

        if(!($page == null))  {
            if(!($page == 1)) {
                $data['page'] = true;
            }
        }

        $data['articles']  = $threads;
        return $this->render('MaximCMSBundle:pages:home.html.twig', $data);
    }

    public function announcementsLoadAction()
    {

        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder()
            ->select('a')
            ->from('MaximCMSBundle:Announcement', 'a')
            ->innerJoin('MaximCMSBundle:Website', 'w', 'WITH', 'a.website = w.id')
            ->where('w.id = :website')
            ->andWhere('a.startdate <= :startdate')
            ->andWhere('a.enddate >= :enddate')
            ->setParameters(array(
                'website'   => $this->container->getParameter('website'),
                'startdate' => new \DateTime(date("F d Y H:i:s")),
                'enddate'   => new \DateTime(date("F d Y H:i:s"))
            ));

        $query = $qb->getQuery();
        $query->useResultCache(true);
        $query->useQueryCache(true);
        $query->setResultCacheLifetime(3600);

        $data['announcements'] = $query->getResult();

        $query = null;

        //print_r($query->getResult());
        return $this->render('MaximCMSBundle:Modules:announcements.html.twig', $data);
    }
    public function custom_pagesAction()
    {
        //get pages
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder()
            ->select('p')
            ->from('MaximCMSBundle:Page', 'p')
            ->innerJoin('MaximCMSBundle:Website', 'w', 'WITH', 'p.website = w.id')
            ->where('w.id = :website')
            ->setParameter('website', $this->container->getParameter('website'));


        $query = $qb->getQuery();
        $query->useResultCache(true);
        $query->useQueryCache(true);
        $query->setResultCacheLifetime(3600);

        $data['pages'] = $query->getResult();
        $query = null;

        $data['applications'] = array();
        $data['showapplications'] = true;

        $response = $this->render('MaximCMSBundle:Navigation:custom.html.twig', $data);
        return $response;
    }

   /* public function postAction($newsId)
	{
		
		$request = $this->getRequest();

			//Load single post with given ID
			$em = $this->getDoctrine()->getManager();
	
			$qb = $em->createQueryBuilder()
			      ->select('c.message, c.id, c.date, u.username')
			      ->from('MaximCMSBundle:Comment', 'c')
			      ->innerJoin('MaximCMSBundle:User', 'u', 'WITH', 'c.user = u.id')
				  ->where('c.newsid = :id')
				  ->setParameter('id', $newsId)
				  ->orderBy('c.date', 'DESC');
			;

            $query = $qb->getQuery();
            $query->useResultCache(true);
            $query->useQueryCache(true);
            $query->setResultCacheLifetime(3600);
			$tags = $query->getResult();
            $query = null;

			$data['comments'] = $tags;
			$data['newsId'] = $newsId;

            if($request->isXmlHttpRequest())
            {
                $engine = $this->container->get('templating');
                $content = $engine->render('MaximCMSBundle:pages:comment.html.twig', $data);

                return new Response(json_encode(array('success' => true, 'message' => $content)));
            }
	        return $this->render('MaximCMSBundle:pages:comment.html.twig', $data);
	}
	public function addAction()
	{
		$request = $this->getRequest();
		if ($request->isXmlHttpRequest()) {
			
			$comment = new Comment();
			
			$news = $this->getDoctrine()
				        ->getRepository('MaximCMSBundle:News')
				        ->find($request->request->get('_news_id'));
						
			$comment->setNewsid($news);
			$comment->setMessage($request->request->get('_news_message'));
			
			$date = date("Y-m-d H:i:s");
			$comment->setDate($date);
			
			#check if user is logged in
			if($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
			{
				$user = $this->get('security.context')->getToken()->getUser();
				$comment->setUser($user);
				
				$validator = $this->get('validator');
	    		$errors = $validator->validate($comment);
				
				 if (count($errors) > 0) {
				 	
				 	$output = "<ul>";
					foreach($errors as $error)
					{
						$output .= "<li>".$error->getMessage()."</li>";
					}
					$output .= "</ul>";
					
			       $result = array('success' => false, 'message' => $output);	
			    } else {
					$em = $this->getDoctrine()->getManager();
				    $em->persist($comment);
					$em->flush();
					$result = array('success' => true, 'message' => 'Comment added succesfuly');	
			    }
			}
			else {
				$result = array('success' => false, 'message' => 'You must be logged in to perform this action');
			}	
		}
		else
		{
			$result = array('success' => false, 'message' => 'Comment could not be added');		
		}
		
		return new Response(json_encode($result));
	}*/
}
