<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Maxim
 * Date: 07/07/13
 * Time: 11:49
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Listeners;


class MenuListener {
    private $logger;
    private $container;
    private $menus;

    public function __construct($logger, $container)
    {
        $this->logger = $logger;
        $this->container = $container;
    }
    public function configureMenu($menu)
    {
        $this->addMenu($menu->getContent());
    }
    public function addMenu($menu)
    {
        $this->menus[] = $menu;
    }

    public function getMenus()
    {
        return $this->menus;
    }
}