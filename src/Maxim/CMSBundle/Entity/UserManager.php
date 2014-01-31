<?php
/**
 * Author: Maxim
 * Date: 13/12/13
 * Time: 15:10
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\User as BaseUser;
use FOS\UserBundle\Model\UserInterface;
use Sonata\CoreBundle\Entity\DoctrineBaseManager;
use FOS\UserBundle\Model\UserManager as BaseUserManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

class UserManager
{
    protected $em;

    protected $repo;

    protected $class;

    public function __construct(EntityManager $em, $class)
    {
        $this->em = $em;
        $this->class = $class;
        $this->repo = $em->getRepository($class);
    }

    public function findLatestUsers($amount)
    {
        return $this->repo->findLatestUsers($amount);
    }
}