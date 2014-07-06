<?php
namespace Maxim\Module\OnlineUsersBundle\Helper;

use Doctrine\Common\Cache\XcacheCache;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Author: Maxim
 * Date: 03/07/2014
 * Time: 20:23
 * Property of MCSuite
 */

class OnlineUsersHelper
{
    protected $users;
    protected $security;
    protected $cache;

    const KEY_GUESTS = "guests";
    const KEY_MEMBERS = "members";
    const KEY_STAFF = "staff";

    const KEY_CACHE = "OnlineUsersHelper_online_players";

    const USERNAME_GUEST = "*g"; //Make sure you dont take a minecraft username

    // timeout in minutes
    const TIMEOUT = 10;

    public function __construct(SecurityContext $security, XcacheCache $cache)
    {
        $this->security = $security;
        $this->cache = $cache;

        $this->users = array(
            self::KEY_GUESTS => array(),
            self::KEY_MEMBERS => array(),
            self::KEY_STAFF => array()
        );
    }

    /**
     * @param $arr
     * @return int total amount of players
     */
    public function count($arr)
    {
        return count($arr[self::KEY_MEMBERS]) + count($arr[self::KEY_STAFF]) + count($arr[self::KEY_GUESTS]);
    }
    /**
     * @return $this->users
     * Fetch current stored values if any
     */
    public function fetch()
    {
        if($c = $this->cache->fetch(self::KEY_CACHE))
        {
            $this->users = unserialize($c);
        }
        return $this->users;
    }

    /**
     * Save new result
     */
    public function save()
    {
        $this->cache->save(self::KEY_CACHE, serialize($this->users));
    }

    /**
     * Checks if a key exists in the second array of users, otherwise add it
     */
    public function add()
    {
        $request = Request::createFromGlobals();

        if($this->security->getToken() != null && $this->security->isGranted('IS_AUTHENTICATED_REMEMBERED'))
        {
            $loggedUser = $this->security->getToken()->getUser();
            $keyToCheck = self::KEY_MEMBERS;

            if($this->security->isGranted('ROLE_STAFF'))
            {
                $keyToCheck = self::KEY_STAFF;
            }

            if(!array_key_exists($loggedUser->getUsername(), $this->users[self::KEY_STAFF] ))
            {
                $this->users[$keyToCheck][$request->getClientIp()] = array("time" => time(), "username" => $loggedUser->getUsername());
            }
        }
        else if($this->security->getToken() != null && $this->security->isGranted('IS_AUTHENTICATED_ANONYMOUSLY'))
        {
            if(!array_key_exists($request->getClientIp(), $this->users[self::KEY_STAFF] ))
            {
                $this->users[self::KEY_GUESTS][$request->getClientIp()] = array("time" => time(), "username" => self::USERNAME_GUEST);
            }
        }

        //TEST DATA
        /*$this->users[self::KEY_GUESTS]["135.68.4.5"] = array("time" => time(), "username" => self::USERNAME_GUEST);
        $this->users[self::KEY_GUESTS]["135.68.4.6"] = array("time" => time(), "username" => self::USERNAME_GUEST);
        $this->users[self::KEY_MEMBERS]["135.68.4.4"] = array("time" => time(), "username" => "Notch");
        $this->users[self::KEY_STAFF]["135.68.2.5"] = array("time" => time(), "username" => "enayet123");
        $this->users[self::KEY_STAFF]["135.68.1.5"] = array("time" => time(), "username" => "shazz96");*/
    }

    /**
     * Removes all sets older than 10 minutes, also remove all existing user records
     */
    public function remove()
    {
        $request = Request::createFromGlobals();

        foreach($this->users as $k => $set)
        {
            foreach($set as $key => $value)
            {
                if($value < time() - (self::TIMEOUT * 60)
                    || $key == $request->getClientIp()
                    || ($this->security->getToken() != null
                        && $this->security->isGranted('IS_AUTHENTICATED_REMEMBERED')
                        && (strtoupper($this->security->getToken()->getUser()->getUsername()) == strtoupper($value['username']))))
                {
                    unset($this->users[$k][$key]);
                }
            }
        }
    }
} 