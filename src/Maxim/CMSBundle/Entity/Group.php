<?php
namespace Maxim\CMSBundle\Entity;
 
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;

class Group extends BaseGroup
{
    const ROLE_ADMIN = "ROLE_ADMIN";
    const ROLE_STAFF = "ROLE_STAFF";
    const ROLE_MEMBER = "ROLE_MEMBER";
    const ROLE_GUEST = "ROLE_GUEST";
    const ROLE_BANNED = "ROLE_BANNED";

    protected $id;

    protected $description;

    protected $default;

    protected $cssClass;

    protected $sections;

    protected $users;

    protected $application;

    public function getRoleList()
    {
        return array(
            self::ROLE_ADMIN => self::ROLE_ADMIN,
            self::ROLE_STAFF => self::ROLE_STAFF,
            self::ROLE_MEMBER => self::ROLE_MEMBER,
            self::ROLE_GUEST => self::ROLE_GUEST,
            self::ROLE_BANNED => self::ROLE_BANNED,
        );
    }

    /**
     * Consturcts a new instance of Role.
     */
    public function __construct($name = NULL, $roles = array())
    {
        parent::__construct($name, $roles);
        $this->applications = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $users
     */
    public function setUsers($users)
    {
        $this->users = $users;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * @param mixed $cssClass
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * @return mixed
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * @param mixed $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return mixed
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $sections
     */
    public function setSections($sections)
    {
        $this->sections = $sections;
    }

    /**
     * @return mixed
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param mixed $application
     */
    public function setApplication($application)
    {
        $this->application = $application;
    }

    /**
     * @return mixed
     */
    public function getApplication()
    {
        return $this->application;
    }

}