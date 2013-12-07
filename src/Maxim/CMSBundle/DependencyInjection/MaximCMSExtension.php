<?php

namespace Maxim\CMSBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Finder\Finder;
use Doctrine\Common\Cache\ApcCache;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MaximCMSExtension extends Extension
{

    /*
     * load Parameters by recursion
     */
    public function loadParameters(ContainerBuilder $container, $parameters, $path) {

        foreach ($parameters as $key => $value) {

            $newPath = $path . '.' . $key ;

            $container->setParameter($newPath, $value);

            if(is_array($container->getParameter($newPath))) {

                $this->loadParameters($container, $container->getParameter($newPath), $newPath);

            }

        }

    }
    /**
     * Load all bundles there services.yml
     */
    public function load(array $configs, ContainerBuilder $container)
    {

        $configuration = new Configuration();

        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter("maxim_cms", $config);
        $this->loadParameters($container, $config, 'maxim_cms');

        // LOAD services.xml
        $cacheDriver = new ApcCache();

        if(!$cacheDriver->contains("_core_services"))
        {
            $exclude = array("AdminBundle");
            $names   = array("services.yml");
            $paths   = array();

            for($i = 0; $i < count($names); $i++)
            {
                $finder  = new Finder();
                $finder->name($names[$i]);

                $configuration = new Configuration();
                $config = $this->processConfiguration($configuration, $configs);

                foreach($finder->in(__DIR__ . "/../../")->exclude($exclude) as $file)
                {
                    $paths[] = array("name" => $names[$i], "path" => substr($file, 0, count($file) - (strlen($names[$i]) + 1)));
                }
            }

            $cacheDriver->save("_core_services", $paths, 3600);
        }
        else
        {
            $paths = $cacheDriver->fetch("_core_services");
        }

        for($i = 0; $i < count($paths); $i++)
        {
            $locator =  new FileLocator(array($paths[$i]["path"]));
            $loader  = new Loader\YamlFileLoader($container, $locator);

            $loader->load($paths[$i]["name"]);
        }
    }
    public function getAlias()
    {
        return 'maxim_cms';
    }
}
