<?php
namespace Maxim\CMSBundle\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Maxim\CMSBundle\Entity\Useraccounts;

class AccountHelper extends controller
{
	
	protected $doctrine;
	protected $security;
	protected $container;
	
    public function __construct($container, $doctrine, $security) {
    	$this->container = $container;
        $this->doctrine = $doctrine;
		$this->security = $security;
    }
	
	public function hasAccountsVerified()
	{
		$user = $this->security->getToken()->getUser();
		
		if(!$user)
		{
			return false;
		}
		
		$em 		= $this->getDoctrine()->getManager();
		$accounts 	= $em->getRepository('MaximCMSBundle:Useraccounts')->findBy(array("user" => $user));
		
		return $accounts;
	}
}
