<?php
/**
 * Project: MCSuite
 * File: CoreApplication.php
 * User: Maxim
 * Date: 25/04/13
 * Time: 21:21
 */

namespace Maxim\CMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Maxim\CMSBundle\Entity\CoreApplication
 *
 * @ORM\Table(name="core_application")
 * @ORM\Entity
 */
class CoreApplication {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    protected $name;

    /**
     * @ORM\ManyToMany(targetEntity="Rank", mappedBy="applications", cascade={"persist"})
     */
    private $ranks;

    public function __construct()
    {
        $this->ranks = new ArrayCollection();
    }
    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
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
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function setRanks($ranks)
    {
        $this->ranks = $ranks;
    }

    public function getRanks()
    {
        return $this->ranks;
    }

    public function addRank(\Maxim\CMSBundle\Entity\Rank $rank)
    {
        $this->ranks[] = $rank;
    }

    public function removeRank(\Maxim\CMSBundle\Entity\Rank $rank)
    {
        $this->ranks->removeElement($rank);
    }
}