<?php
namespace Maxim\CMSBundle\Entity;
 
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * @ORM\Entity
 * @ORM\Table(name="rank")
 */
class Rank implements RoleInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var integer $id
     */
    protected $id;
 
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string $name
     */
    protected $name;

	/**
     * @ORM\Column(type="string", length=255)
     *
     * @var string $roleName
     */
	protected $roleName;

	 /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string $roleName
     */
    protected $description;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var string $default
     */
    protected $default;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $cssClass;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="CoreApplication", inversedBy="ranks", cascade={"persist"})
     * @ORM\JoinTable(name="permissiongroup",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rank_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $applications;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Maxim\Module\TicketBundle\Entity\TicketSection", inversedBy="ranks", cascade={"persist"})
     * @ORM\JoinTable(name="ticket_section_rank",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rank_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     *   }
     * )
     */
    protected $sections;

    /**
     * Gets the id.
     *
     * @return integer The id.
     */
    public function getId()
    {
        return $this->id;
    }
	public function getApplication()
	{
		return $this->application;
	}
	public function getDescription()
	{
		return $this->description;
	}
    /**
     * Gets the role name.
     *
     * @return string The name.
     */
    public function getName()
    {
        return $this->name;
    }
 
    /**
     * Sets the role name.
     *
     * @param string $value The name.
     */
    public function setName($value)
    {
        $this->name = $value;
    }
    
  	/**
     * Sets the description.
     *
     * @param string $value The name.
     */
    public function setDescription($value)
    {
        $this->description = $value;
    }

    /**
     * Sets the roleName.
     *
     * @param string $roleName The roleName.
     */
    public function setRoleName($roleName)
    {
        $this->roleName = $roleName;
    }
 	public function setApplication($application)
	{
		$this->application = $application;
	}
    public function addApplication(\Maxim\CMSBundle\Entity\CoreApplication $application)
    {
        $application->addRank($this);
        $this->applications[] = $application;
    }

    public function removeApplication(\Maxim\CMSBundle\Entity\CoreApplication $application)
    {
        $this->applications->removeElement($application);
        $application->removeRank($this);
        return $this;
    }
    /**
     * Consturcts a new instance of Role.
     */
    public function __construct($name = NULL, $roleName = NULL)
    {
    	$this->name = $name;
    	$this->roleName = $roleName;
        $this->applications = new ArrayCollection();
    }

    public function setApplications($applications)
    {
        $this->applications = $applications;
    }

    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * @param string $default
     */
    public function setDefault($default)
    {
        $this->default = $default;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $sections
     */
    public function setSections($sections)
    {
        $this->sections = $sections;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @param string $cssClass
     */
    public function setCssClass($cssClass)
    {
        $this->cssClass = $cssClass;
    }

    /**
     * @return string
     */
    public function getCssClass()
    {
        return $this->cssClass;
    }

    /**
     * Returns the role.
     *
     * This method returns a string representation whenever possible.
     *
     * When the role cannot be represented with sufficient precision by a
     * string, it should return null.
     *
     * @return string|null A string representation of the role, or null
     */
    public function getRole()
    {
        return $this->roleName;
    }

    function __toString()
    {
        return $this->name;
    }

}