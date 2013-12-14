<?php
namespace Maxim\CMSBundle\Listeners;
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

    public function __construct(SecurityContext $context, $container, array $query = array())
    {
        $this->securityContext = $context;
        $this->container = $container;
        $this->query = $query;
        $this->cacheDriver = new ApcCache();
    }
    public function onKernelController(FilterControllerEvent $event)
    {
        $em = $this->getDoctrine()->getManager();


        $query = $em->createQuery("SELECT cu FROM MaximCMSBundle:CoreApplication cu");

        //$this->cacheDriver->save("_pageload_coreapplications", $applications, 10);
        $query->useResultCache(true, 3600, '_pageload_coreapplications');

        $applications = $query->getResult();

        $request = $event->getRequest();
        // Matched route
        //$_route  = $request->attributes->get('_route');

        //$pattern = $request->attributes->get('_pattern');
        // Matched controller
        $_controller = $request->attributes->get('_controller');

        // All route parameters including the `_controller`
        //$params      = $request->attributes->get('_route_params');

        $test = explode(':', $_controller);
        //get applications

        $permission = $this->get('permission.helper');
        foreach($applications as $application)
        {
           // echo $application->getName();
            if($application->getName()."Controller" == substr($test[0], 29, strlen($_controller)))
            {
                $permission->hasPermission($application);
            }
        }
    }
}
