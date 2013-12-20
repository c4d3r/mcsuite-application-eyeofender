<?php
/**
 * Author: Maxim
 * Date: 15/11/13
 * Time: 10:38
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

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
    public function findLatestThread($user)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT t
            FROM MaximModuleForumBundle:Thread t
            JOIN t.createdBy u
            WHERE u.id = :user
            ORDER BY t.createdOn DESC"
        );
        $query->setParameter("user", $user->getId());
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function findNewsPosts($websiteid)
    {
        return $this->getEntityManager()->createQuery(
            "SELECT t, f, c, w
            FROM MaximModuleForumBundle:Thread t
            INNER JOIN t.forum f
            INNER JOIN f.category c
            INNER JOIN c.website w
            WHERE w.id = :websiteid
            AND f.showOnHome = true
            ORDER BY t.createdOn DESC
            "
        )
            ->setParameters(array("websiteid" => $websiteid))
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->useResultCache(true, 3600, __METHOD__ . serialize("website_newsposts"));
        ;
    }
}