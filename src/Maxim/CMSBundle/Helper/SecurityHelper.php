<?php
namespace Maxim\CMSBundle\Helper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityHelper extends controller
{
	protected $doctrine;
	protected $security;
	protected $container;
	
    public function __construct($container, $doctrine, $security) {
    	$this->container = $container;
        $this->doctrine = $doctrine;
		$this->security = $security;
    }
	
	
	public function encrypt($string, $key)
	{
		return base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
	}
	public function decrypt($string, $key)
	{
		return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	}
}
?>