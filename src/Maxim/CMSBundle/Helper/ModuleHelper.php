<?php
/**
 * Project: MCSuite
 * File: ModuleHelper.php
 * User: Maxim
 * Date: 16/03/13
 * Time: 10:58
 */

namespace Maxim\CMSBundle\Helper;
class ModuleHelper{

    protected $security;
    protected $container;

    public function __construct($container, $security) {
        $this->container = $container;
        $this->security = $security;
    }

    public function loadConfig($fileName)
    {
        $config = $this->container->container->getParameter('maxim_cms');
        return $config;
    }

}