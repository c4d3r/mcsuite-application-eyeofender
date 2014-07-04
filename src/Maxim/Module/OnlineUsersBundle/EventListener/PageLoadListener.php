<?php
/**
 * Author: Maxim
 * Date: 03/07/2014
 * Time: 19:21
 * Property of MCSuite
 */
namespace Maxim\Module\OnlineUsersBundle\EventListener;

use Maxim\Module\OnlineUsersBundle\Helper\OnlineUsersHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Cache;
use Symfony\Component\Security\Core\SecurityContext;

class PageLoadListener
{
    protected $helper;

    public function __construct(OnlineUsersHelper $helper)
    {
        $this->helper = $helper;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $this->helper->fetch();
        $this->helper->remove();
        $this->helper->add();
        $this->helper->save();
    }
} 