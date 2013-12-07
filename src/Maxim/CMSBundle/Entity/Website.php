<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 14/08/13
 * Time: 12:06
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validation\Constraints AS Assert;
/**
 * Maxim\CMSBundle\Entity\Website
 *
 * @ORM\Entity
 * @ORM\Table(name="website")
 */
class Website {
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=45, nullable=true)
     */
    protected $name;

    /**
     * @var string $content
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var string $site
     *
     * @ORM\Column(name="site", type="text", nullable=true)
     */
    protected $site;

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

    /**
     * @param string $site
     */
    public function setSite($site)
    {
        $this->site = $site;
    }

    /**
     * @return string
     */
    public function getSite()
    {
        return $this->site;
    }

    public function __toString() {
        return $this->name;
    }
}