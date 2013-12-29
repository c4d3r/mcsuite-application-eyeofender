<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Maxim\CMSBundle\Entity\FriendRequest;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Maxim\CMSBundle\Component\Validator\Constraints as CustomAssert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Yaml\Yaml;
/**
 * @ORM\Entity(repositoryClass="Maxim\CMSBundle\Entity\UserRepository")
 * @UniqueEntity(fields="username", message="Username already taken")
 * @ORM\Table(name="user")
 */
class User implements AdvancedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	

    /**
     * @var string $username
     * @ORM\Column(name="username", type="string", length=16, nullable=false)
     */
    private $username;

    /**
     * @var string $password
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     */
    private $password;

	private $passwordConfirm;
    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     */
    private $email;

    /**
     * @var string $lastip
     *
     * @ORM\Column(name="lastIp", type="string", length=45, nullable=true)
     */
    private $lastip;

    /**
     * @var \DateTime $lastlogin
     *
     * @ORM\Column(name="lastLogin", type="datetime", nullable=true)
     */
    private $lastlogin;

	/**
     * @var string $location
     *
     * @ORM\Column(name="location", type="text", nullable=true)
     */
	private $location;
	
	/**
     * @var string $skype
     *
     * @ORM\Column(name="skype", type="text", nullable=true)
     */
	private $skype;

    /**
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=true)
     */
    private $country;

    /**
     * @var string $street
     *
     * @ORM\Column(name="street", type="text", nullable=true)
     */
    private $street;

    /**
     * @var string $city
     *
     * @ORM\Column(name="city", type="text", nullable=true)
     */
    private $city;

    /**
     * @var string $housenumber
     *
     * @ORM\Column(name="housenumber", type="text", nullable=true)
     */
    private $housenumber;

    /**
     * @var string $postalcode
     *
     * @ORM\Column(name="postalcode", type="text", nullable=true)
     */
    private $postalcode;

    /**
     * @var string $state
     *
     * @ORM\Column(name="state", type="text", nullable=true)
     */
    private $state;

    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="text", nullable=true)
     */
    private $telephone;
	
    /**
     * @var \DateTime $dateofbirth
     *
     * @ORM\Column(name="dateOfBirth", type="datetime", nullable=true)
     */
    private $dateofbirth;

	 /**
     * @var \DateTime $registeredOn
     *
     * @ORM\Column(name="registeredOn", type="datetime", nullable=true)
     */
    private $registeredOn;
	
    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Rank", fetch="EXTRA_LAZY")
     * @ORM\JoinTable(name="user_rank")
     */
    private $ranks;
	
	/**
     * @var integer $verified
     *
     * @ORM\Column(name="verified", type="integer", nullable=false)
     */
	private $verified = 0;

    /**
     * @var Friend
     *
     * @ORM\OneToMany(targetEntity="UserFriend", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $friends;

    /**
     * @var FriendRequest
     *
     * @ORM\OneToMany(targetEntity="FriendRequest", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $friendRequests;

    /**
     * @var Notification
     *
     * @ORM\OneToMany(targetEntity="Notification", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $notifications;

    /**
     * @ORM\OneToMany(targetEntity="Announcement", mappedBy="user", fetch="EXTRA_LAZY")
     */
    protected $announcements;

    /**
     * @ORM\OneToMany(targetEntity="\Maxim\Module\ForumBundle\Entity\Thread", mappedBy="createdBy")
     */
    protected $threads;

    /**
     * @ORM\OneToMany(targetEntity="\Maxim\Module\ForumBundle\Entity\Post", mappedBy="createdBy")
     */
    protected $posts;

    /**
     * @ORM\OneToMany(targetEntity="\Maxim\Module\ForumBundle\Entity\ThreadEdit", mappedBy="updatedBy")
     */
    protected $threadedits;

    /**
     * @ORM\OneToMany(targetEntity="\Maxim\Module\ForumBundle\Entity\PostEdit", mappedBy="updatedBy")
     */
    protected $postedits;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="user")
     */
    protected $purchases;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ranks = new ArrayCollection();
		$this->userRoles = new ArrayCollection();
        $this->friends = new ArrayCollection();
        $this->friendRequests = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->threads = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
	/**
     * Gets the user roles.
     *
     * @return ArrayCollection A Doctrine ArrayCollection
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }
    /**
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = htmlentities($username);
    
        return $this;
    }
	/**
     * Gets the user salt.
     *
     * @return string The salt.
     */
    public function getSalt()
    {
        $salt = "RJGL0VX857tr7wWps8V69reU";   // This salt can be anything you want but just remember the user is created with a salt, their password will be encrypted using it. 
        return $salt;    // So if this changes, then the user won't be able to log in. So make sure this value won't change for the user 
                              // (I'm thinking about using the timestamp that the user signed up. Please let me know if that's a bad idea.)
    }
    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;
    
        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }
	
	/**
     * Set PasswordConfirm
     *
     * @param string $PasswordConfirm
     * @return User
     */
    public function setPasswordConfirm($passwordConfirm)
    {
        $this->passwordConfirm = $passwordConfirm;
    
        return $this;
    }

    /**
     * Get PasswordConfirm
     *
     * @return string 
     */
    public function getPasswordConfirm()
    {
        return $this->passwordConfirm;
    }
	
    /**
     * Set email
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;
    
        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

	/**
     * Get location
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->location;
    }
	
	/**
     * Set location
     *
     * @param string $location
     * @return User
     */
    public function setLocation($location)
    {
        $this->location = $location;
    
        return $this;
    }
	
	/**
     * Get location
     *
     * @return string 
     */
    public function getLocation()
    {
        return $this->location;
    }
	
	/**
     * Set location
     *
     * @param string $location
     * @return User
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;
    
        return $this;
    }
	
	/**
     * Get skype
     *
     * @return string 
     */
    public function getSkype()
    {
        return $this->skype;
    }
	
    /**
     * Set lastip
     *
     * @param string $lastip
     * @return User
     */
    public function setLastip($lastip)
    {
        $this->lastip = $lastip;
    
        return $this;
    }

    /**
     * Get lastip
     *
     * @return string 
     */
    public function getLastip()
    {
        return $this->lastip;
    }

    /**
     * Set lastlogin
     *
     * @param \DateTime $lastlogin
     * @return User
     */
    public function setLastlogin($lastlogin)
    {
        $this->lastlogin = $lastlogin;
    
        return $this;
    }

    /**
     * Get lastlogin
     *
     * @return \DateTime 
     */
    public function getLastlogin()
    {
        return $this->lastlogin;
    }

    /**
     * Set dateofbirth
     *
     * @param \Date $dateofbirth
     * @return User
     */
    public function setDateofbirth(\DateTime $dateofbirth = null)
    {
        $this->dateofbirth = $dateofbirth ? clone $dateofbirth : null;
    
        return $this;
    }

    /**
     * Get dateofbirth
     *
     * @return \DateTime 
     */
    public function getDateofbirth()
    {
        return $this->dateofbirth;
    }

 	/**
     * Set registeredOn
     *
     * @param \DateTime $registeredOn
     * @return User
     */
    public function setRegisteredOn($registeredOn)
    {
        $this->registeredOn = $registeredOn;
    
        return $this;
    }

    /**
     * Get registeredOn
     *
     * @return \DateTime 
     */
    public function getRegisteredOn()
    {
        return $this->registeredOn;
    }
	
	/**
     * Set verified
     *
     * @param integer $verified
     * @return User
     */
    public function setVerified($verified)
    {
        $this->verified = $verified;
    
        return $this;
    }

    /**
     * Get verified
     *
     * @return integer 
     */
    public function getVerified()
    {
        return $this->verified;
    }


    /**
     * @param Rank $rank
     * @return $this
     */
    public function addRank(\Maxim\CMSBundle\Entity\Rank $rank)
    {
        $this->ranks[] = $rank;
    
        return $this;
    }

    /**
     * @param Rank $rank
     */
    public function removeRank(\Maxim\CMSBundle\Entity\Rank $rank)
    {
        $this->ranks->removeElement($rank);
    }

    /**
     * Get rank
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRank()
    {
        // Returns primary rank
        $primaryRank = null;
        if(isset($this->ranks)) {
            foreach($this->ranks as $rank) {
                if($primaryRank == null)
                {
                    $primaryRank = $rank;
                }
                else
                {
                    if($rank->getId() < $primaryRank->getId()) {
                        $primaryRank = $rank;
                    }
                }
            }
        }


        if($primaryRank == null) {

            $tmpRank = new Rank("Member", "ROLE_MEMBER");
            $tmpRank->setCssClass("rank-member");
            return $tmpRank;
        }
        return $primaryRank;
    }

    public function getRanks()
    {
        return $this->ranks;
    }

    public function isGranted($role)
    {
        $roles = array();
        if(!isset($this->ranks)) {
            return false;
        }

        foreach ($this->ranks as $rank) {
            $roles[] = $rank->getRole();
        }

        foreach($role as $r)
        {
            if(in_array($r, $roles))
            {
                return true;
            }
        }
        return false;
    }

	public function getRoles()
    {
		$ranks = array();
        foreach ($this->ranks as $rank) {
        	$ranks[] = new Rank($rank->getName(), $rank->getRole());
        }
		if(count($ranks) == 0)
		{
			$ranks[] = new Rank("Member", "ROLE_MEMBER");
		}
		//print_r($roles);
        return $ranks;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $housenumber
     */
    public function setHousenumber($housenumber)
    {
        $this->housenumber = $housenumber;
    }

    /**
     * @return string
     */
    public function getHousenumber()
    {
        return $this->housenumber;
    }

    /**
     * @param string $postalcode
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
    }

    /**
     * @return string
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $street
     */
    public function setStreet($street)
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }


    /**
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }


	 /**
     * Erases the user credentials.
     */
    public function eraseCredentials()
    {
 
    }
	 public function equals($user)
    {
        return md5($this->getUsername()) == md5($user->getUsername());
    }
	
	 public function isAccountNonExpired()
    {
        return true;
    }

    public function isAccountNonLocked()
    {
        return true;
    }

    public function isCredentialsNonExpired()
    {
        return true;
    }

    public function isEnabled()
    {
        return true;
    } 
	public function isPasswordMatch()
	{
		return ( $this->password == $this->passwordConfirm );
	}
    public function isEmailMatchUsername()
    {
        return ( strtolower($this->username) == strtolower($this->email) );
    }

    public function __toString() {
        return $this->username;
    }

    public function removeFriend(User $friend)
    {
        $this->friends->removeElement($friend);
    }
    /**
     * @param $friend
     */
    public function addFriend($friend) {
        $this->friends[] = $friend;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Thread $friends
     */
    public function setFriends($friends)
    {
        $this->friends = $friends;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Thread
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\Thread $friendRequests
     */
    public function setFriendRequests($friendRequests)
    {
        $this->friendRequests = $friendRequests;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\Thread
     */
    public function getFriendRequests()
    {
        return $this->friendRequests;
    }

    public function hasFriendRequest(User $player)
    {
        foreach($this->friendRequests as $fr)
        {
            if($fr->getRecipient()->getId() == $player->getId() || $fr->getUser()->getId() == $player->getId()) {
                return true;
            }
        }
        return false;
    }

    /*
     * NOTIFICATIONS
     */
    public function getNotifications() {
        return $this->notifications;
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

    public function setNotifications($notifications) {
        $this->notifications = $notifications;
    }

    public function addNotification($notification) {
        $this->notifications[] = $notification;
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
     * @param mixed $posts
     */
    public function setPosts($posts)
    {
        $this->posts = $posts;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * @param mixed $threads
     */
    public function setThreads($threads)
    {
        $this->threads = $threads;
    }

    /**
     * @return mixed
     */
    public function getThreads()
    {
        return $this->threads;
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



}