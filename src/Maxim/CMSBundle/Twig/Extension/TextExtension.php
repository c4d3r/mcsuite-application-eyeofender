<?php
/**
 * Author: Maxim
 * Date: 01/11/13
 * Time: 17:00
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Twig\Extension;


use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\SecurityContext;

class TextExtension extends \Twig_Extension
{

    protected $doctrine;
    protected $context;

    public function __construct(RegistryInterface $doctrine, SecurityContext $context)
    {
        $this->doctrine = $doctrine;
        $this->context = $context;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('cutlength', array($this, 'cutlength')),
            new \Twig_SimpleFilter('timeago', array($this, 'timeago')),
            new \Twig_SimpleFilter('prettydate', array($this, 'prettydate'))
        );
    }

    public function cutLength($text, $length = 10, $addition = '...')
    {
        if(strlen($text) < $length)
        {
            return $text;
        }
        return substr($text, 0, $length) . $addition;
    }

    public function timeAgo($time)
    {
        $time = time() - $time; // to get the time since that moment

        $tokens = array (
            31536000 => 'year',
            2592000 => 'month',
            604800 => 'week',
            86400 => 'day',
            3600 => 'hour',
            60 => 'minute',
            1 => 'second'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
        }
    }

    public function prettyDate($date)
    {
        $now = new \DateTime("now");
        $day = $date->format('j');
        $dayNow = $now->format('j');
        $diff = ($dayNow - $day);

        if($diff <= 1)
        {
            $dayWord = (($diff == 1) ? "Yesterday" : "Today");
        }
        elseif($diff >= 2 && $diff <= 6)
        {
            $dayWord = $date->format('l');
        }
        else
        {
            $dayWord = $date->format('M j');
        }
        return sprintf("%s at %s", $dayWord, $date->format('g:i a'));
    }

    public function getNode($node)
    {
        $text = $this->doctrine->getRepository("MaximCMSBundle:CoreNode")->findOneBy(array("node" => $node));

        if(!$text)
        {
            return "";
        }

        return $text->getContent();

    }

    public function getName()
    {
        return 'text_extension';
    }

    public function getFunctions()
    {
        return array(
            'getNode' => new \Twig_Function_Method($this, 'getNode', array('is_safe' => array('html')))
        );
    }
}