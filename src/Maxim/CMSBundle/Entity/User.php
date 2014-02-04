<?php
/**
 * Author: Maxim
 * Date: 28/01/14
 * Time: 15:19
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Maxim\CMSBundle\Model\User as BaseUser;
use Maxim\CMSBundle\Model\UserInterface;

class User extends BaseUser
{

    protected $id;

    protected $mcUsername;

    protected $mcPassword;

    public function getGroup()
    {
        if($this->groups[0] != null)
            return $this->groups[0];

        $group = new Group('Member', array('ROLE_MEMBER'));
        $group->setCssClass('group-member');
        return $group;

    }
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Hook on pre-persist operations
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime;
        $this->updatedAt = new \DateTime;
    }

    /**
     * Hook on pre-update operations
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime;
    }

    /**
     * Returns the gender list
     *
     * @return array
     */
    public static function getGenderList()
    {
        return parent::getGenderList();
    }

    /**
     * @param mixed $mcUsername
     */
    public function setMcUsername($mcUsername)
    {
        $this->mcUsername = $mcUsername;
    }

    /**
     * @return mixed
     */
    public function getMcUsername()
    {
        return $this->mcUsername;
    }

    /**
     * @param mixed $mcPassword
     */
    public function setMcPassword($mcPassword)
    {
        $this->mcPassword = $mcPassword;
    }

    /**
     * @return mixed
     */
    public function getMcPassword()
    {
        return $this->mcPassword;
    }

} 