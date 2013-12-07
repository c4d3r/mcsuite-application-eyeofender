<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Maxim
 * Date: 27/07/13
 * Time: 15:53
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Maxim\CMSBundle\Entity\Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity
 */
class Country {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     *
     * @var integer $id
     */
    protected $id;

    /**
     * @var string $iso2
     *
     * @ORM\Column(name="iso2", type="text", nullable=true)
     */
    protected $iso2;

    /**
     * @var string $shortName
     *
     * @ORM\Column(name="short_name", type="text", nullable=true)
     */
    protected $shortName;

    /**
     * @var string $longName
     *
     * @ORM\Column(name="long_name", type="text", nullable=true)
     */
    protected $longName;

    /**
     * @var string $numcode
     *
     * @ORM\Column(name="numcode", type="text", nullable=true)
     */
    protected $numcode;

    /**
     * @var string $unMember
     *
     * @ORM\Column(name="un_member", type="text", nullable=true)
     */
    protected $unMember;

    /**
     * @var string $callingCode
     *
     * @ORM\Column(name="calling_code", type="text", nullable=true)
     */
    protected $callingCode;

    /**
     * @var string $cctld
     *
     * @ORM\Column(name="cctld", type="text", nullable=true)
     */
    protected $cctld;

    /**
     * @param string $callingCode
     */
    public function setCallingCode($callingCode)
    {
        $this->callingCode = $callingCode;
    }

    /**
     * @return string
     */
    public function getCallingCode()
    {
        return $this->callingCode;
    }

    /**
     * @param string $cctld
     */
    public function setCctld($cctld)
    {
        $this->cctld = $cctld;
    }

    /**
     * @return string
     */
    public function getCctld()
    {
        return $this->cctld;
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
     * @param string $iso2
     */
    public function setIso2($iso2)
    {
        $this->iso2 = $iso2;
    }

    /**
     * @return string
     */
    public function getIso2()
    {
        return $this->iso2;
    }

    /**
     * @param string $longName
     */
    public function setLongName($longName)
    {
        $this->longName = $longName;
    }

    /**
     * @return string
     */
    public function getLongName()
    {
        return $this->longName;
    }

    /**
     * @param string $numcode
     */
    public function setNumcode($numcode)
    {
        $this->numcode = $numcode;
    }

    /**
     * @return string
     */
    public function getNumcode()
    {
        return $this->numcode;
    }

    /**
     * @param string $shortName
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
    }

    /**
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * @param string $unMember
     */
    public function setUnMember($unMember)
    {
        $this->unMember = $unMember;
    }

    /**
     * @return string
     */
    public function getUnMember()
    {
       return (strtoupper($this->unMember)  == "YES");
    }
}