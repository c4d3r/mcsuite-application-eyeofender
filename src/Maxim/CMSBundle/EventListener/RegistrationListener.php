<?php
/**
 * Author: Maxim
 * Date: 22/06/2014
 * Time: 22:08
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\EventListener;


use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Maxim\CMSBundle\Helper\MinecraftHelper;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

class RegistrationListener implements EventSubscriberInterface
{
    /** @var \Doctrine\ORM\EntityManager */
    private $em;

    /** @var \Maxim\CMSBundle\Helper\MinecraftHelper */
    private $minecraft;

    public function __construct(Doctrine $doctrine, MinecraftHelper $minecraft)
    {
        $this->em = $doctrine->getManager();
        $this->minecraft = $minecraft;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_COMPLETED => 'onRegistrationComplete',
        );
    }

    /**
     * @param FilterUserResponseEvent $event
     */
    public function onRegistrationComplete(FilterUserResponseEvent $event)
    {
        $user = $event->getUser();
        $user->setMcUuid($this->minecraft->fetchUUID($user->getUsername()));
        $this->em->flush();

        return;
    }
}