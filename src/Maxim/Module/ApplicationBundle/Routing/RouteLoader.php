<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 20/08/13
 * Time: 11:30
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ApplicationBundle\Routing;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;

class RouteLoader extends Loader implements LoaderInterface {

    private $loaded = false;

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add this loader twice');
        }

        $collection = new RouteCollection();

        $resource = __DIR__ . '/../Resources/config';
        $importedRoutes = $this->import($resource, $type);

        $collection->addCollection($importedRoutes);

        $this->loaded = true;

        return $collection;
    }

    public function supports($resource, $type = null)
    {
        return 'advanced_extra' === $type;
    }

    public function reload()
    {
        $this->loaded = false;
    }

    public function getResolver()
    {
    }
    public function setResolver(LoaderResolverInterface $resolver)
    {
        // irrelevant to us, since we don't need a resolver
    }
}