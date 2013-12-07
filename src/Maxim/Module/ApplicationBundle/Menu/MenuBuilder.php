<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Maxim
 * Date: 07/07/13
 * Time: 11:43
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ApplicationBundle\Menu;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Maxim\CMSBundle\Event\MenuEvent;

class MenuBuilder {
    private $dispatcher;
    private $logger;
    private $doctrine;

    public function __construct(EventDispatcherInterface $dispatcher, $logger, $container, $doctrine)
    {
        $this->dispatcher = $dispatcher;
        $this->logger = $logger;
        $this->container = $container;
        $this->doctrine = $doctrine;
    }

    /**
     * Main function for building menus and dispatching related events.
     */
    public function buildMenu()
    {
        $em = $this->doctrine->getManager();
        $website = $em->getRepository('MaximCMSBundle:Website')->findOneBy(array("id" => $this->container->getParameter('website')));
        $applications = $em->getRepository('MaximModuleApplicationBundle:Application')->findBy(array("website" => $website));
        $data['applications'] = $applications;

        $event = new MenuEvent($this->container->get('templating')->render('MaximModuleApplicationBundle:Navigation:applications.html.twig', $data));
        $this->dispatcher->dispatch('maxim_cms.event_menu', $event);

    }
}