<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 20/08/13
 * Time: 10:50
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class RoutingLoader extends Loader{

    private $doctrine;
    private $logger;
    private $container;
    private $loaded;

    public function __construct($doctrine, $logger, $container) {

        $this->doctrine  = $doctrine;
        $this->logger    = $logger;
        $this->container = $container;

    }

    public function load($resource, $type = null) {

        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        //$modules = $this->doctrine->getRepository('MaximCMSBundle:Module')->findBy(array("activated" => 1));
        $collection = new RouteCollection();

        /* FIND ROUTING FILES IN BUNDLES AND LOAD THOSE */

        $exclude = array("CMSBundle", "InstallBundle", "AdminBundle");
        $names   = array("routing.yml", "routing_admin.yml");

        for($i = 0; $i < count($names); $i++)
        {
            $finder  = new Finder();
            $finder->name($names[$i]);

            foreach($finder->in(__DIR__ . "/../../")->exclude($exclude) as $file)
            {
                $locator    = new FileLocator(array(substr($file, 0, count($file) - (strlen($names[$i]) + 1))));
                $loader     = new YamlFileLoader($locator);

                $collection->addCollection($loader->load($names[$i]));
            }
        }

        return $collection;

        /*if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $collection = new RouteCollection();
                                 echo "hi";
        // get all Bundles
        $bundles = $this->container->getParameter('kernel.bundles');
        foreach($bundles as $bundle) {
            if(isset($bundle)) {

                   echo $bundle;

            }
        }


        /*$resource = '@AcmeDemoBundle/Resources/config/import_routing.yml';
        $type = 'yaml';

        $importedRoutes = $this->import($resource, $type);

        $collection->addCollection($importedRoutes);

        return $collection;          */
    }

    public function supports($resource, $type = null)
    {
        return $type === 'advanced_extra';
    }
    public function reload()
    {
        $this->loaded = false;
    }
}