<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 15/09/13
 * Time: 10:39
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Maxim\CMSBundle\Entity\Announcement
 *
 * @ORM\Table(name="announcement")
 * @ORM\Entity
 */
class Announcement {

    const TYPE_ERROR   = "error";
    const TYPE_WARNING = "warning";
    const TYPE_INFO    = "info";
    const TYPE_SUCCESS = "success";

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var Website
     *
     * @ORM\ManyToOne(targetEntity="Website", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="website_id", referencedColumnName="id", nullable=true)
     */
    protected $website;

    /**
     * @var string $text
     *
     * @ORM\Column(name="text", type="string", nullable=true)
     */
    protected $text;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     */
    protected $type;

    /**
     * @var datetime $startdate
     *
     * @ORM\Column(name="startdate", type="datetime", nullable=false)
     */
    protected $startdate;

    /**
     * @var datetime $enddate
     *
     * @ORM\Column(name="enddate", type="datetime", nullable=true)
     */
    protected $enddate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="announcements", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /**
     * @var datetime $createdOn
     *
     * @ORM\Column(name="createdOn", type="datetime", nullable=false)
     */
    protected $createdOn;


    public function __construct() {
        $this->setCreatedOn(new \DateTime(date('Y-m-d H:i:s')));
    }

    public static function getTypeList()
    {
        return array(
            self::TYPE_ERROR  => 'Error',
            self::TYPE_INFO   => 'Info',
            self::TYPE_SUCCESS => 'Success',
            self::TYPE_WARNING => 'Warning',
        );
    }

    /**
     * @param \DateTime $enddate
     */
    public function setEnddate($enddate)
    {
        $this->enddate = $enddate;
    }

    /**
     * @return \DateTime
     */
    public function getEnddate()
    {
        return $this->enddate;
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
     * @param \DateTime $startdate
     */
    public function setStartdate($startdate)
    {
        $this->startdate = $startdate;
    }

    /**
     * @return \DateTime
     */
    public function getStartdate()
    {
        return $this->startdate;
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param \DateTime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
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
     * @param \Maxim\CMSBundle\Entity\Website $website
     */
    public function setWebsite(Website $website)
    {
        $this->website = $website;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Website
     */
    public function getWebsite()
    {
        return $this->website;
    }

}