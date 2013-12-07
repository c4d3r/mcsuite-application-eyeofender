<?php
/**
 * Author: Maxim
 * Date: 04/11/13
 * Time: 19:21
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Maxim\CMSBundle\Entity\CoreNodes
 *
 * @ORM\Table(name="core_node")
 * @ORM\Entity
 */
class CoreNode
{
    /**
     * @var string $description
     *
     * @ORM\Id
     * @ORM\Column(name="node", type="string", length=255, nullable=false)
     */
    protected $node;

    /**
     * @var string $description
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    protected $content;

    /**
     * @var \datetime $createdOn
     *
     * @ORM\Column(name="createdOn", type="datetime", nullable=false)
     */
    protected $createdOn;

    /**
     * @var \datetime $updatedOn
     *
     * @ORM\Column(name="updatedOn", type="datetime", nullable=true)
     */
    protected $updatedOn;

    public function __construct()
    {
        $addedOn = new \DateTime("now");
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param \datetime $createdOn
     */
    public function setCreatedOn($createdOn)
    {
        $this->createdOn = $createdOn;
    }

    /**
     * @return \datetime
     */
    public function getCreatedOn()
    {
        return $this->createdOn;
    }

    /**
     * @param string $node
     */
    public function setNode($node)
    {
        $this->node = $node;
    }

    /**
     * @return string
     */
    public function getNode()
    {
        return $this->node;
    }

    /**
     * @param \datetime $updatedOn
     */
    public function setUpdatedOn($updatedOn)
    {
        $this->updatedOn = $updatedOn;
    }

    /**
     * @return \datetime
     */
    public function getUpdatedOn()
    {
        return $this->updatedOn;
    }


}