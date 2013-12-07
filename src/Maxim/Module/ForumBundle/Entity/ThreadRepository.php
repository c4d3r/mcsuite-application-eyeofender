<?php
/**
 * Author: Maxim
 * Date: 15/11/13
 * Time: 10:38
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;
use Doctrine\ORM\EntityRepository;

class ThreadRepository extends EntityRepository
{
    public function findLatestThreads($amount, $website)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t, f, u, c
                FROM MaximModuleForumBundle:Thread t
                JOIN t.forum f
                JOIN t.createdBy u
                JOIN f.category c
                WHERE c.website = :website
                ORDER BY t.createdOn DESC'
            )
            ->setParameter("website", $website)
            ->setMaxResults($amount)
            ->getResult();
    }
}