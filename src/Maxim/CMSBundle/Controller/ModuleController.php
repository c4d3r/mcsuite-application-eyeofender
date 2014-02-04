<?php
namespace Maxim\CMSBundle\Controller;

use Maxim\CMSBundle\Entity\Uservote;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Maxim\CMSBundle\Controller\MinecraftController;

/* USED BY MODULES */
use Maxim\CMSBundle\Controller\AccountController;
use Maxim\CMSBundle\Entity\User;
use Maxim\CMSBundle\Entity\Shout;
use Maxim\CMSBundle\Entity\Pollanswer;
use Maxim\CMSBundle\Entity\Pollquestion;
use Maxim\CMSBundle\Entity\Pollresult;


class ModuleController extends Controller
{
    const BUNDLE_MAXIMCMS = "MaximCMSBundle";

    protected $version;
    protected $name = array();
    protected $config;
    protected $container;
    protected $bundle;

    public function __construct()
    {
        $this->bundle = "";
    }

    # overwrite render view
    public function render($view, array $parameters = array(), Response $response = null)
    {
        if($this->get('templating')->exists(self::BUNDLE_MAXIMCMS . ':' . $view))
        {
            return parent::render(self::BUNDLE_MAXIMCMS . ':' . $view, $parameters);
        }
        else
        {
            return parent::render($this->bundle . ':' . $view, $parameters);
        }
    }

    public function executeQuery($query) {

        $config = $this->container->container->getParameter('maxim_cms');

        try
        {
            $conn = new \PDO('mysql:host='.$config['server']['mysql']['host'].';dbname='.$config['server']['mysql']['db'], $config['server']['mysql']['user'], $config['server']['mysql']['pass']);
            $conn->query($query);
            return array("success" => true);
        }
        catch(\Exception $ex)
        {
            return array("success" => false, "message" => $ex->getMessage());
        }


    }

    public function accountExists($account)
    {
        $em 			= $this->getDoctrine()->getManager();
        $repository 	= $em->getRepository('MaximCMSBundle:Useraccounts');
        $accountExists 	= $repository->findOneBy(array("accountname" => $account));

        return (( !$accountExists == false) ? true : false);
    }
    public function isOnline($username)
    {
        $minecraft = $this->get('minecraft.helper');
        $details = $minecraft->send(array("SpecialCMD=@ONLINELIST"), true);
        $onlinePlayers = explode(',', $details[0]);

        return in_array($username, $onlinePlayers);
    }
    public function getMinecraftAccounts()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->get('security.context')->getToken()->getUser();

        $qb = $em->createQueryBuilder()
            ->select('ua')
            ->from('MaximCMSBundle:Useraccounts', 'ua')
            ->where('ua.user = :id')
            ->setParameter('id', $user)
        ;
        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function sec_to_dhms($sec, $show_days = false){
        $days = intval($sec / 86400);
        $hours = intval(($sec / 3600) % 24);
        $minutes = intval(($sec / 60) % 60);
        $seconds = intval($sec % 60);
        return $hours.":".$minutes.":".$seconds;
    }

    public static function parseCommand($tags, $text)
    {
        if (sizeof($tags) > 0)
        {
            foreach ($tags as $tag => $data)
            {
                $text = str_replace("{" . $tag . "}", $data, $text);
            }
        }
        return $text;
    }
    public static function calcAge($dateOfBirth)
    {
        //date in mm/dd/yyyy format; or it can be in other formats as well
        $birthDate = $dateOfBirth->format('m/d/Y');
        //explode the date to get month, day and year
        $birthDate = explode("/", $birthDate);
        //get age from date or birthdate
        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md") ? ((date("Y")-$birthDate[2])-1):(date("Y")-$birthDate[2]));
        return $age;
    }
}
