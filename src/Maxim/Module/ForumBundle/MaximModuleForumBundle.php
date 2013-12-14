<?php

namespace Maxim\Module\ForumBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Maxim\Module\ForumBundle\DependencyInjection\MaximModuleForumExtension;

class MaximModuleForumBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        // register extensions that do not follow the conventions manually
        $container->registerExtension(new MaximModuleForumExtension());
    }
}