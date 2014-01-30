<?php

namespace Maxim\Module\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApplicationReply
 *
 * @ORM\Table(name="application_reply")
 * @ORM\Entity
 */
class ApplicationReply
{

    protected $id;

    protected $text;

    protected $date;

    protected $application;

    protected $user;


    public function __construct()
    {
        $this->setDate(new \DateTime("now"));
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Set text
     *
     * @param string $text
     * @return ApplicationReply
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return ApplicationReply
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set application
     *
     * @param Application $application
     * @return ApplicationReply
     */
    public function setApplication(UserApplication $application)
    {
        $this->application = $application;

        return $this;
    }

    /**
     * Get application
     *
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set user
     *
     * @param \Maxim\CMSBundle\Entity\User $user
     * @return ApplicationReply
     */
    public function setUser(\Maxim\CMSBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}