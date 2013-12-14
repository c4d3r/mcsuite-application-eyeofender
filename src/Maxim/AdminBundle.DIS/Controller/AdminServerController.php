<?php
/**
 * Project: MCSuite
 * File: AdminServerController.php
 * User: Maxim
 * Date: 27/04/13
 * Time: 20:00
 */

namespace Maxim\AdminBundle\Controller;
use Maxim\CMSBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Yaml\Yaml;
use LanKit\DatatablesBundle\Datatables\DataTable;
use Maxim\CMSBundle\Entity\Server;
use Maxim\CMSBundle\Entity\ServerIp;

class AdminServerController extends Controller{

    public function serverListViewAction()
    {
        return $this->render('MaximCMSBundle:admin:server/view.html.twig');
    }
    public function serverAddViewAction()
    {
        return $this->render('MaximCMSBundle:admin:server/add.html.twig');
    }
    public function serverDeleteAction()
    {

    }

    public function serverListAction()
    {

    }

    public function serverAddAction()
    {

    }
    public function getServersJsonAction($websiteid)
    {
        $em = $this->getDoctrine()->getManager();
        $website = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $websiteid));
        $servers = $em->getRepository('MaximCMSBundle:Server')->findBy(array("website" => $website));

        $output = array();
        foreach($servers as $server) {
            $output[] = array(
                "id"            => $server->getId(),
                "name"          => $server->getName(),
                "description"   => $server->getDescription(),
                "image"         => $server->getImage(),
                "abbr"          => $server->getAbbr()
            );
        }
        return new Response(json_encode($output));
    }
}