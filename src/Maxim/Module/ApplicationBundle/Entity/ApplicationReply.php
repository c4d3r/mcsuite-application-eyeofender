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
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    private $id;
    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime", nullable=false)
     */
    private $date;

    /**
     * @var UserApplication
     *
     * @ORM\ManyToOne(targetEntity="UserApplication", inversedBy="replies")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="application_user_id", referencedColumnName="id")
     * })
     */
    private $application;

    /**
     * @var \User
     * @ORM\ManyToOne(targetEntity="\Maxim\CMSBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;


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