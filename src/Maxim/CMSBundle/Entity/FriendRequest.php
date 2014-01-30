<?php
namespace Maxim\CMSBundle\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Maxim\CMSBundle\Entity\FriendRequest
 *
 * @ORM\Table(name="friend_request")
 * @ORM\Entity
 */
class FriendRequest {

    const STATE_PENDING = "PENDING";
    const STATE_ACCEPT  = "APPROVED";
    const STATE_DENY    = "IGNORED";

    protected $recipient;

    protected $user;

    protected $requestedOn;

    protected $changedOn;

    protected $state;

    public function __construct() {
        $this->requestedOn = new \DateTime("now");
    }

    /**
     * @param \DateTime $changedOn
     */
    public function setChangedOn($changedOn)
    {
        $this->changedOn = $changedOn;
    }

    /**
     * @return \DateTime
     */
    public function getChangedOn()
    {
        return $this->changedOn;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\User $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param \DateTime $requestedOn
     */
    public function setRequestedOn($requestedOn)
    {
        $this->requestedOn = $requestedOn;
    }

    /**
     * @return \DateTime
     */
    public function getRequestedOn()
    {
        return $this->requestedOn;
    }


    /**
     * @param $state
     * @throws \InvalidArgumentException
     */
    public function setState($state)
    {
        if(!in_array($state, array(self::STATE_PENDING, self::STATE_ACCEPT, self::STATE_DENY))){
            throw new \InvalidArgumentException("Invalid state specified for state");
        }
        $this->state = $state;
    }

    /**
     * @return String
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param \Maxim\CMSBundle\Entity\User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return \Maxim\CMSBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }



}