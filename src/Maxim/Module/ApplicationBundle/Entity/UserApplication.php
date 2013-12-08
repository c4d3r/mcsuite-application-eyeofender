<?php

namespace Maxim\Module\ApplicationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserApplication
 *
 * @ORM\Table(name="user_application")
 * @ORM\Entity
 */
class UserApplication {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User")
     */
    private $user;

    /**
     * @var json_array $details
     *
     * @ORM\Column(name="details", type="json_array", nullable=false)
     */
    private $details;

    /**
     * @var \DateTime $date
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var integer $denied
     *
     * @ORM\Column(name="denied", type="integer", nullable=false)
     */
    private $denied = 0;

    /**
     * @var Application
     *
     * @ORM\ManyToOne(targetEntity="Application")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id", nullable=true)
     */
    private $application;

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

    function __toString()
    {
        return "";
    }

}
