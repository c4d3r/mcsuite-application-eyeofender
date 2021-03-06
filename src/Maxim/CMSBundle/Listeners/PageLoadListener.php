<?php
namespace Maxim\CMSBundle\Listeners;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class PageLoadListener
{
    private $securityContext;
    protected $container;
    protected $query;
    protected $router;
    protected $cacheDriver;

    public function __construct(SecurityContext $context, $container, array $query = array())
    {
        $this->securityContext = $context;
        $this->container = $container;
        $this->query = $query;
    }
    public function onKernelController(FilterControllerEvent $event)
    {
       // $em = $this->getDoctrine()->getManager();
       // $logger = $this->container->get('logger');
    }
}
