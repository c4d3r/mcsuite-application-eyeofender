<?php
/**
 * Author: Maxim
 * Date: 13/12/13
 * Time: 15:10
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\EntityManager;
use Sonata\CoreBundle\Entity\DoctrineBaseManager;

class UserManager extends DoctrineBaseManager
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