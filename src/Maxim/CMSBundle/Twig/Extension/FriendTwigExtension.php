<?php
/**
 * Author: Maxim
 * Date: 27/10/13
 * Time: 11:56
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Twig\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\SecurityContext;

class FriendTwigExtension extends \Twig_Extension
{
    protected $doctrine;
    protected $context;

    public function __construct(RegistryInterface $doctrine, SecurityContext $context)
    {
        $this->doctrine = $doctrine;
        $this->context = $context;
    }

    public function hasFriendRequest($player)
    {
        //Check if the player has a friendrequest on the current user
        $user = $this->context->getToken()->getUser();

        // Get both friendrequests
        foreach($player->getFriendRequests() as $request)
        {
            if($request->getRecipient()->getId() == $user->getId() || $request->getUser()->getId() == $user->getId()) {
                return true;
            }
        }
    }

    public function isFriend($user, $friend)
    {
        foreach($user->getFriends() as $f)
        {
            if($f->getFriend()->getId() == $friend->getId() || $f->getUser()->getId() == $friend->getId())
            {
                return true;
            }
        }
        return false;
    }

    public function getName()
    {
        return 'friend';
    }

    public function getFunctions()
    {
        return array(
            'hasFriendRequest' => new \Twig_Function_Method($this, 'hasFriendRequest'));
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('isFriend', array($this, 'isFriend')),
        );
    }

}