<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 15/09/13
 * Time: 10:37
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Event;


use Symfony\Component\EventDispatcher\Event;

class AnnouncementEvent extends Event{

    protected $text;
    protected $type;
    protected $start;
    protected $end;

    function __construct($text, $type, \DateTime $start, \DateTime $end)
    {
        $this->end = $end;
        $this->start = $start;
        $this->text = $text;
        $this->type = $type;
    }

    /**
     * @param mixed $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return mixed
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $start
     */
    public function setStart($start)
    {
        $this->start = $start;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }


}