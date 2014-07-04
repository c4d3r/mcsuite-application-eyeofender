<?php
namespace Maxim\Module\OnlineUsersBundle\Controller;

use Maxim\Module\OnlineUsersBundle\Helper\OnlineUsersHelper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Author: Maxim
 * Date: 03/07/2014
 * Time: 19:19
 * Property of MCSuite
 */

class OnlineUsersController extends Controller
{
    public function showAction()
    {
        $helper = $this->container->get('maxim_online_players.helper');

        $arr = $helper->fetch();

        $data['total'] = $helper->count($arr);
        $data['categories'] = $arr;

        return $this->render('MaximModuleOnlineUsersBundle:Module/OnlineUsers:show.html.twig', $data);
    }
} 