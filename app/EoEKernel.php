<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class EoEKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            # MY BUNDLES
            new Exercise\HTMLPurifierBundle\ExerciseHTMLPurifierBundle(), //purify html to prevent xss
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(), // generate menus
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(), //paginator bundle
            new JMS\SerializerBundle\JMSSerializerBundle($this),

            new JMS\AopBundle\JMSAopBundle(),
            new JMS\SecurityExtraBundle\JMSSecurityExtraBundle(),
            new JMS\DiExtraBundle\JMSDiExtraBundle($this),

            # Maxim Bundles
            //new Maxim\AdminBundle\MaximAdminBundle(),
            new Maxim\CMSBundle\MaximCMSBundle(),
            //new Maxim\InstallBundle\MaximInstallBundle(),
            new Maxim\Module\ForumBundle\MaximModuleForumBundle(),
            
            # Theme
            new Maxim\Theme\EOEBundle\MaximThemeEOEBundle(),

            # general modules
            new Maxim\Module\ApplicationBundle\MaximModuleApplicationBundle(),
            new Maxim\Module\TicketBundle\MaximModuleTicketBundle(),

            # payment methods
            new Payum\Bundle\PayumBundle\PayumBundle(), // payum

            # Sonata
            new Sonata\CoreBundle\SonataCoreBundle(), // a dependency on sonataadminbundle
            new Sonata\jQueryBundle\SonatajQueryBundle(),// a dependency on sonataadminbundle
            new Sonata\DoctrineORMAdminBundle\SonataDoctrineORMAdminBundle(), // a dependency on sonataadminbundle
            new Sonata\AdminBundle\SonataAdminBundle(),   // the sonata admin bundle
            new Sonata\BlockBundle\SonataBlockBundle(),   //used for blocks on the acp
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
