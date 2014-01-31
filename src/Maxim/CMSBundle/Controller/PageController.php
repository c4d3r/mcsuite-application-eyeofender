<?php

namespace Maxim\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class PageController extends Controller
{
    public function internalAction($page)
    {
    	$logger = $this->container->get('logger');
		$logger->err('INTERNAL: '.$page);
    	$config = $this->container->getParameter('maxim_cms.general');
        return $this->redirect($config['domain'].$page);
    }
	public function externalAction($page)
	{
		return $this->redirect($page);
	}
	public function renderPageAction($page)
	{
		# GET PAGE FROM DATABASE
		$em 		= $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder()
            ->select('p')
            ->from('MaximCMSBundle:Page', 'p')
            ->innerJoin('MaximCMSBundle:Website', 'w', 'WITH', 'p.website = w.id')
            ->where('w.id = :website')
            ->andWhere('p.url = :page')
            ->setParameter('website', $this->container->getParameter('website'))
            ->setParameter(':page', $page )
            ->orderBy('p.id', 'DESC');

        $query = $qb->getQuery();
        $query->useResultCache(true);
        $query->useQueryCache(true);
        $query->setResultCacheLifetime(3600);

        $result = $query->getResult();
		if(!$page || (count($result) <= 0))
		{
            return $this->render('MaximCMSBundle:Exception:error404.html.twig');
		}
		else
		{

            $data['page'] = array("name" => $result[0]->getName(), "content" => $result[0]->getContent());
            return $this->render('MaximCMSBundle:pages:custom.html.twig', $data);

		}
	}
    public function forumAction()
    {
        $config = $this->container->getParameter('maxim_cms.pages');
        return $this->redirect($config['forum']);
    }
}
