<?php
/**
 * Author: Maxim
 * Date: 23/11/13
 * Time: 20:56
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table("currency")
 * @ORM\Entity
 */
class Currency
{
    /**
     * @ORM\Id
     * @ORM\Column(name="short", type="string", length=3)
     */
    protected $short;

    /**
     * @ORM\Column(name="symbol", type="string", length=3)
     */
    protected $symbol;

    /**
     * @ORM\Column(name="fullname", type="text")
     */
    protected $fullname;

    /**
     * @ORM\Column(name="description", type="text")
     */
    protected $description;

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
     * @param mixed $fullname
     */
    public function setFullname($fullname)
    {
        $this->fullname = $fullname;
    }

    /**
     * @return mixed
     */
    public function getFullname()
    {
        return $this->fullname;
    }

    /**
     * @param mixed $short
     */
    public function setShort($short)
    {
        $this->short = $short;
    }

    /**
     * @return mixed
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * @param mixed $symbol
     */
    public function setSymbol($symbol)
    {
        $this->symbol = $symbol;
    }

    /**
     * @return mixed
     */
    public function getSymbol()
    {
        return $this->symbol;
    }
}