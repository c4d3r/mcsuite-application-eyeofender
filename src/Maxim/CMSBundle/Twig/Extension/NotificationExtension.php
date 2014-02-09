<?php
/**
 * Author: Maxim
 * Date: 08/02/14
 * Time: 16:33
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Twig\Extension;


class NotificationExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('buildNotificationText', array($this, 'buildNotificationText')),
        );
    }

    public function buildNotificationText($text, $receiver, $user)
    {
        return $text;
    }

    public function getName()
    {
        return 'notification_extension';
    }

    public function getFunctions()
    {
        return array(
            'getNode' => new \Twig_Function_Method($this, 'getNode', array('is_safe' => array('html')))
        );
    }
} 