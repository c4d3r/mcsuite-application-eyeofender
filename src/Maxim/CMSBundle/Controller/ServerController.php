<?php
namespace Maxim\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Maxim\CMSBundle\Controller\MinecraftController;

class ServerController extends Controller {
	
	
	public function playerAction($player)
	{
		$data['player'] = $player;
		return $this->render('MaximCMSBundle:Modules:player.html.twig', $data);
	}
	
	public function onlineAction()
	{
        $minecraft = $this->get('minecraft.helper');

        $details = $minecraft->send(array("SpecialCMD=@ONLINELIST"), true);

        $onlinePlayers = explode(',', $details[0]);
		$data['onlinePlayers'] = (count($onlinePlayers) > 0 && count($onlinePlayers[0] > 2)) ? $onlinePlayers : 0;
		return $this->render('MaximCMSBundle:Modules:online.html.twig', $data);
	}

    /*
     * View a server his specifications and details with a given id
     */
    public function viewAction($id)
    {
        // get the server
        $server = $this->getDoctrine()->getRepository('MaximCMSBundle:Server')->findOneBy(array("id" => $id));
        if(!$server)
        {
            throw $this->createNotFoundException(
                'No server found with id:  '.$id
            );
        }

        // Get server ip's
        $ip = $this->getDoctrine()->getRepository('MaximCMSBundle:ServerIp')->findBy(array("server" => $server));
        if(!$ip)
        {
            throw $this->createNotFoundException(
                'No ips found for server with id:  '.$id
            );
        }

        $data['server'] = $server;
        $data['serverips'] = $ip;
        return $this->render('MaximCMSBundle:pages:server.html.twig', $data);
    }
}
