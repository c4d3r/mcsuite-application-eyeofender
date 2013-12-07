<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Listeners;


use Doctrine\ORM\EntityManager;
use Maxim\CMSBundle\Event\UserEvent;

class UserListener {

    protected $doctrine;

    public function __construct(EntityManager $em)
    {
        $this->doctrine = $em;
    }
    public function onUserUpdate(UserEvent $event)
    {
        $this->doctrine->persist($event->getUser());
        $this->doctrine->flush();
    }
}