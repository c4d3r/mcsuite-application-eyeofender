<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 15/09/13
 * Time: 10:48
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Listeners;


use Doctrine\ORM\EntityManager;
use Maxim\CMSBundle\Entity\Announcement;
use Maxim\CMSBundle\Event\AnnouncementEvent;
use Monolog\Logger;

class AnnouncementListener {

    protected $logger;
    protected $doctrine;

    public function __construct(Logger $logger, EntityManager $doctrine)  {
        $this->setLogger($logger);
        $this->setDoctrine($doctrine);
    }

    public function onAnnouncementCreate(AnnouncementEvent $event)
    {
        $announcement = new Announcement();
        $announcement->setText($event->getText());
        $announcement->setType($event->getType());
        $announcement->setStartdate($event->getStart());
        $announcement->setEnddate($event->getEnd());
        $announcement->setUser($event->getUser());

        $this->doctrine->persist($announcement);
        $this->doctrine->flush();
    }

    /**
     * @param mixed $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * @return mixed
     */
    public function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @param mixed $logger
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;
    }

    /**
     * @return mixed
     */
    public function getLogger()
    {
        return $this->logger;
    }


}