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

    protected $mcUuid;

    protected $awards;

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

    public function isGranted($role)
    {
        foreach($this->getGroups() as $g)
        {
            foreach($g->getRoles() as $role)
            {
                if(strtoupper($role) == strtoupper($role))
                    return true;
            }
        }
    }

    public function isBanned()
    {
        return $this->isGranted(Group::ROLE_BANNED);
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

    /**
     * @return mixed
     */
    public function getMcUuid()
    {
        return $this->mcUuid;
    }

    /**
     * @param mixed $mcUuid
     */
    public function setMcUuid($mcUuid)
    {
        $this->mcUuid = $mcUuid;
    }

    /**
     * @return mixed
     */
    public function getAwards()
    {
        return $this->awards;
    }

    /**
     * @param mixed $awards
     */
    public function setAwards($awards)
    {
        $this->awards = $awards;
    }

    public function addAward($award)
    {
        $this->awards[] = $award;
    }
} 