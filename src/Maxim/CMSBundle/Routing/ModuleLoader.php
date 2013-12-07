<?php
/**
 * Project: MCSuite
 * File: ModuleLoader.php
 * User: Maxim
 * Date: 13/04/13
 * Time: 13:31
 */

namespace Maxim\CMSBundle\Routing;

use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;

class ModuleLoader extends Loader implements LoaderInterface {

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

       /* if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        //$modules = $this->doctrine->getRepository('MaximCMSBundle:Module')->findBy(array("activated" => 1));
        $collection = new RouteCollection();

        // FIND ROUTING FILES IN BUNDLES AND LOAD THOSE

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

        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $collection = new RouteCollection();

        // get all Bundles
        $bundles = $this->container->getParameter('kernel.bundles');
        foreach($bundles as $bundle) {
            if(isset($bundle)) {

                $bundleName = explode('\\', $bundle);
                $resource = '@' . $bundleName[count($bundleName) - 1] . '/Resources/config/routing.yml';

                $importedRoutes = $this->import($resource, 'yaml');
                $collection->addCollection($importedRoutes);
            }
        } */


        /*$resource = '@AcmeDemoBundle/Resources/config/import_routing.yml';
        $type = 'yaml';

        $importedRoutes = $this->import($resource, $type);

        $collection->addCollection($importedRoutes);

        return $collection; */
    }

    public function supports($resource, $type = null)
    {
        return 'module' === $type;
    }

    public function getResolver()
    {
    }
    public function reload()
    {
        $this->loaded = false;
    }
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // irrelevant to us, since we don't need a resolver
    }
}