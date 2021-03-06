<?php

namespace Maxim\Module\ApplicationBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

class UserApplication
{

    protected $id;

    protected $user;

    protected $details;

    protected $date;

    protected $denied = false;

    protected $application;

    protected $replies;


    /**
     * @param Application $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $denied
     */
    public function setDenied($denied)
    {
        $this->denied = $denied;
    }

    /**
     * @return int
     */
    public function getDenied()
    {
        return $this->denied;
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
     * @param \Maxim\CMSBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\json_array $details
     */
    public function setDetails($details)
    {
        $this->details = $details;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\json_array
     */
    public function getDetails()
    {
        return $this->details;
    }

    public function getApplicationEntityName()
    {
        return "MaximModuleApplicationBundle:Application";
    }

    public function __toString()
    {
        return "";
    }

    /**
     * @param \Maxim\Module\ApplicationBundle\Entity\ApplicationReply $replies
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;
    }

    /**
     * @return \Maxim\Module\ApplicationBundle\Entity\ApplicationReply
     */
    public function getReplies()
    {
        if($this->replies == NULL) {
            return new ArrayCollection();
        }
        return $this->replies;
    }

}
