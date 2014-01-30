<?php
/**
 * Author: Maxim
 * Date: 28/01/14
 * Time: 14:58
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Model;
use Doctrine\Common\Collections\ArrayCollection;
use Maxim\CMSBundle\Entity\Group;
use FOS\UserBundle\Model\User as BaseUser;

abstract class User extends BaseUser
{
    protected $location;

    protected $phone;

    protected $dateOfBirth;

    protected $gender = UserInterface::GENDER_UNKNOWN;

    protected $skype;

    protected $facebookUid;

    protected $facebookName;

    protected $facebookData;

    protected $twitterUid;

    protected $twitterName;

    protected $twitterData;

    protected $gplusUid;

    protected $gplusName;

    protected $gplusData;

    protected $biography;

    protected $lastIp;

    protected $verified = false;

    protected $createdAt;

    protected $updatedAt;

    protected $timezone;

    protected $friends;

    protected $friendRequests;

    protected $notifications;

    protected $announcements;

    protected $threads;

    protected $posts;

    protected $threadedits;

    protected $postedits;

    protected $purchases;

    protected $groups;


    public function __construct()
    {
        parent::__construct();
        $this->friends          = new ArrayCollection();
        $this->friendRequests   = new ArrayCollection();
        $this->posts            = new ArrayCollection();
        $this->threads          = new ArrayCollection();
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
        return array(
            UserInterface::GENDER_UNKNOWN => 'gender_unknown',
            UserInterface::GENDER_FEMALE  => 'gender_female',
            UserInterface::GENDER_MALE    => 'gender_male',
        );
    }

    public function getUnreadNotifications() {
        $notifications = array();
        foreach($this->notifications as $notification) {

            if($notification->getReadOn() == null) {
                $notifications[] = $notification;
            }
        }
        return $notifications;
    }

    /**
     * @param mixed $announcements
     */
    public function setAnnouncements($announcements)
    {
        $this->announcements = $announcements;
    }

    /**
     * @return mixed
     */
    public function getAnnouncements()
    {
        return $this->announcements;
    }

    /**
     * @param mixed $biography
     */
    public function setBiography($biography)
    {
        $this->biography = $biography;
    }

    /**
     * @return mixed
     */
    public function getBiography()
    {
        return $this->biography;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $dateOfBirth
     */
    public function setDateOfBirth($dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
    }

    /**
     * @return mixed
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * @param mixed $facebookData
     */
    public function setFacebookData($facebookData)
    {
        $this->facebookData = $facebookData;
    }

    /**
     * @return mixed
     */
    public function getFacebookData()
    {
        return $this->facebookData;
    }

    /**
     * @param mixed $facebookName
     */
    public function setFacebookName($facebookName)
    {
        $this->facebookName = $facebookName;
    }

    /**
     * @return mixed
     */
    public function getFacebookName()
    {
        return $this->facebookName;
    }

    /**
     * @param mixed $facebookUid
     */
    public function setFacebookUid($facebookUid)
    {
        $this->facebookUid = $facebookUid;
    }

    /**
     * @return mixed
     */
    public function getFacebookUid()
    {
        return $this->facebookUid;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $friendRequests
     */
    public function setFriendRequests($friendRequests)
    {
        $this->friendRequests = $friendRequests;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFriendRequests()
    {
        return $this->friendRequests;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $friends
     */
    public function setFriends($friends)
    {
        $this->friends = $friends;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * @param mixed $gplusData
     */
    public function setGplusData($gplusData)
    {
        $this->gplusData = $gplusData;
    }

    /**
     * @return mixed
     */
    public function getGplusData()
    {
        return $this->gplusData;
    }

    /**
     * @param mixed $gplusName
     */
    public function setGplusName($gplusName)
    {
        $this->gplusName = $gplusName;
    }

    /**
     * @return mixed
     */
    public function getGplusName()
    {
        return $this->gplusName;
    }

    /**
     * @param mixed $gplusUid
     */
    public function setGplusUid($gplusUid)
    {
        $this->gplusUid = $gplusUid;
    }

    /**
     * @return mixed
     */
    public function getGplusUid()
    {
        return $this->gplusUid;
    }

    /**
     * @param mixed $lastIp
     */
    public function setLastIp($lastIp)
    {
        $this->lastIp = $lastIp;
    }

    /**
     * @return mixed
     */
    public function getLastIp()
    {
        return $this->lastIp;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $notifications
     */
    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    /**
     * @return mixed
     */
    public function getNotifications()
    {
        return $this->notifications;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $postedits
     */
    public function setPostedits($postedits)
    {
        $this->postedits = $postedits;
    }

    /**
     * @return mixed
     */
    public function getPostedits()
    {
        return $this->postedits;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $purchases
     */
    public function setPurchases($purchases)
    {
        $this->purchases = $purchases;
    }

    /**
     * @return mixed
     */
    public function getPurchases()
    {
        return $this->purchases;
    }

    /**
     * @param mixed $groups
     */
    public function setGroups($groups)
    {
        $this->groups = $groups;
    }

    /**
     * @return mixed
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * @param mixed $skype
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    }

    /**
     * @return mixed
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param mixed $threadedits
     */
    public function setThreadedits($threadedits)
    {
        $this->threadedits = $threadedits;
    }

    /**
     * @return mixed
     */
    public function getThreadedits()
    {
        return $this->threadedits;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $threads
     */
    public function setThreads($threads)
    {
        $this->threads = $threads;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getThreads()
    {
        return $this->threads;
    }

    /**
     * @param mixed $timezone
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
    }

    /**
     * @return mixed
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @param mixed $twitterData
     */
    public function setTwitterData($twitterData)
    {
        $this->twitterData = $twitterData;
    }

    /**
     * @return mixed
     */
    public function getTwitterData()
    {
        return $this->twitterData;
    }

    /**
     * @param mixed $twitterName
     */
    public function setTwitterName($twitterName)
    {
        $this->twitterName = $twitterName;
    }

    /**
     * @return mixed
     */
    public function getTwitterName()
    {
        return $this->twitterName;
    }

    /**
     * @param mixed $twitterUid
     */
    public function setTwitterUid($twitterUid)
    {
        $this->twitterUid = $twitterUid;
    }

    /**
     * @return mixed
     */
    public function getTwitterUid()
    {
        return $this->twitterUid;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param boolean $verified
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    }

    /**
     * @return boolean
     */
    public function getVerified()
    {
        return $this->verified;
    }

} 