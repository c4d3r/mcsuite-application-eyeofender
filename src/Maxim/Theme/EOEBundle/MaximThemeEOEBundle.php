<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 18/08/13
 * Time: 18:59
 * To change this template use File | Settings | File Templates.
 */
namespace Maxim\Theme\EOEBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class MaximThemeEOEBundle extends bundle
{
    public function getParent()
    {
        return 'MaximCMSBundle';
    }
}