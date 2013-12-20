<?php
/**
 * Project: MCSuite
 * File: PermissionHelper.php
 * User: Maxim
 * Date: 25/04/13
 * Time: 23:38
 */

namespace Maxim\CMSBundle\Helper;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PermissionHelper{

    protected $security;
    protected $doctrine;
    protected $logger;

    public function __construct($security, $doctrine, $logger) {
        $this->security = $security;
        $this->doctrine = $doctrine;
        $this->logger   = $logger;
    }

    public function hasPermission($application)
    {
        if(!$application) { throw $this->createNotFoundException("Could not found application"); }
        $user = $this->security->getToken()->getUser();
        if($user)
        {
            foreach($user->getRanks() as $rank)
            {
                foreach($application->getRanks() as $arank)
                {
                    if($arank->getId() == $rank->getId())
                    {
                        return true;
                    }
                }
            }
            throw new AccessDeniedException();
        }
    }
}