<?php
/**
 * An MCSuite product - Sixyce studios
 * File: Section.php
 * User: Maxim
 * Date: 09/02/13
 * Time: 12:23
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\Section
 *
 * @ORM\Table(name="shop_section")
 * @ORM\Entity
 */
class Section {
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * @var string $description
     *
     * @ORM\Column(name="description", type="string", nullable=true)
     */
    private $description;

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
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

    public function __toString() {
        return  $this->name;
    }

}