<?php

namespace Maxim\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use LanKit\DatatablesBundle\Datatables\DataTable;

use Doctrine\ORM\PersistentCollection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Yaml\Yaml;

class AjaxController extends Controller {

	#MODULE
	public function moduleActiveAction()
	{
		$request = $this->getRequest();
		$logger = $this->container->get('logger');
	    
		if ($request->isXmlHttpRequest()) {
			try
			{
				$id 	= $request->request->get('_module_id');
				$active = $request->request->get('_module_active');
				
				$em = $this->getDoctrine()->getManager();
				
				$repository   = $this->getDoctrine()->getRepository('MaximCMSBundle:Module');
				$module = $repository->findOneBy(array('id' => $id));
				
				if($module)
				{
					//CHECK IF 0 OR 1
					$active = (($active == 0) || ($active == 1)) ? $active : 0;
					$module->setActivated($active);
					$em->flush();
					
					//REMOVE SESSION
					$session = $request->getSession();              
					$session->remove("modules_active");
                    $session->save();
                    session_write_close();

                    //RELOAD ROUTING
                    $loader = $this->get('module.loader');
                    $loader->reload();

					$output = array("success" => true, "message" => "Module changed");
				}
				else
				{
					$logger->error('MODULE: could not find the module with id: '.$id);
					$output = array("success" => false, "message" => "could not find the module with id: ".$id);
				}

				
			}
			catch(\Exception $ex)
			{
				
			}
		}
		else
		{
			$logger->error("MODULE: Got none POST request");
			$output = array("success" => false, "message" => "not a post request");	
		}
		
		return new Response(json_encode($output));
	}
}