<?php
/**
 * Author: Maxim
 * Date: 27/04/2014
 * Time: 16:00
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Twig\Extension;


class SeoExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('seo', array($this, 'seo'))
        );
    }

    public function seo($link)
    {
        $link = str_replace(" ", "-", $link);
        $link = str_replace("&", "and", $link);

        return $link;
    }



    public function getName()
    {
        return 'seo_extension';
    }
}