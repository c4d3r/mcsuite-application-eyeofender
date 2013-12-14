<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 20:39
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Event;

use Maxim\CMSBundle\Entity\StoreItem;
use Symfony\Component\EventDispatcher\Event;

class MinecraftSendEvent extends Event{

    protected $commands;

    public function __construct($commands = array())
    {
        $this->commands = $commands;
    }

    /**
     * @param array $commands
     */
    public function setCommands($commands)
    {
        $this->commands = $commands;
    }

    /**
     * @return array
     */
    public function getCommands()
    {
        return $this->commands;
    }

    public function addCommand($command)
    {
        $this->commands[] = $command;
    }
}