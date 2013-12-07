<?php
namespace Maxim\CMSBundle\Twig\Extension;

use Symfony\Component\HttpKernel\KernelInterface;
use Maxim\CMSBundle\Entity\Useraccounts;
use Maxim\CMSBundle\Entity\Userfriend;
use Symfony\Bundle\FrameworkBundle\Templating\GlobalVariables;

class FriendExtension extends \Twig_Extension
{
    protected $doctrine;
	protected $security;
	
    public function __construct($doctrine, $security) {
        $this->doctrine = $doctrine;
		$this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'is_profileOwner' => new \Twig_Function_Method($this, 'isProfileOwner')
        );
    }
    
    /**
     * Converts a string to time
     * 
     * @param string $string
     * @return int 
     */
    public function isProfileOwner ($username)
    {
    	#First check if user is logged in
    	if($this->security->isGranted('IS_AUTHENTICATED_FULLY'))
		{
			$user = $this->security->getToken()->getUser();
			
	    	$em = $this->doctrine->getManager();
		    $userAccount = $em->getRepository('MaximCMSBundle:Useraccounts')->findOneBy(array("accountname" => $username, "user" => $user));
			
			if(!$userAccount)
			{
				return "false";
			}
			else
			{
				return "true";
			}
		}
		else
		{
			return "false";
		}
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'is_profileOwner';
    }
}