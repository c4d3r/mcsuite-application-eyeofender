<?php
namespace Maxim\Module\ApplicationBundle\Listeners;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Doctrine\Common\Cache\ApcCache;
class PageLoadListener extends controller
{
    private $securityContext;
    protected $container;
    protected $query;
    protected $router;
    protected $cacheDriver;
    private $loaded = false;

    public function __construct(SecurityContext $context, $container, array $query = array())
    {
        $this->securityContext = $context;
        $this->container = $container;
        $this->query = $query;
        $this->cacheDriver = new ApcCache();
    }
    public function onKernelController(FilterControllerEvent $event)
    {
        if (!$this->loaded) {
            $this->container->get('maxim_application.menu_builder')->buildMenu();
            $this->loaded = true;
        }
    }
}
