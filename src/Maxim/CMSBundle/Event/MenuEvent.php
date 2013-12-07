<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Maxim
 * Date: 07/07/13
 * Time: 14:41
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Event;
use Symfony\Component\EventDispatcher\Event;
class MenuEvent extends Event
{
    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }
    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

}