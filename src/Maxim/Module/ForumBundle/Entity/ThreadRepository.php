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

    const CACHE_THREAD_BYID = "cache_thread_byid";

    public function findLatestThreads($amount, $website)
    {
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t, u, f
                FROM MaximModuleForumBundle:Thread t
                INNER JOIN t.forum f
                INNER JOIN t.createdBy u
                INNER JOIN f.category c
                WHERE c.website = :website
                AND t.state = :state
                ORDER BY t.createdOn DESC'
            )
            ->setParameter("website", $website)
            ->setParameter("state", Thread::THREAD_STATE_VISIBLE)
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->setMaxResults($amount)
            ->useResultCache(true, 60, __METHOD__ . serialize("website_forum_latestthreads"))
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
        return $this->getEntityManager()
            ->createQuery(
                'SELECT t, p, u, f
                FROM MaximModuleForumBundle:Thread t
                LEFT JOIN t.posts p
                INNER JOIN t.forum f
                INNER JOIN f.category c
                INNER JOIN c.website w
                INNER JOIN t.createdBy u
                WHERE f.showOnHome = true AND w.id = :website
                AND t.state = :state
                ORDER BY t.createdOn DESC
                '
            )
            ->setParameter("website", $websiteid)
            ->setParameter("state", Thread::THREAD_STATE_VISIBLE)
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->useResultCache(true, 3600, __METHOD__ . serialize("website_newsposts"))
            ->getArrayResult();
    }
    public function findThreadById($threadid)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT t, f, u, g, p
            FROM MaximModuleForumBundle:Thread t
            JOIN t.forum f
            JOIN t.createdBy u
            LEFT JOIN u.groups g
            LEFT JOIN u.posts p
            WHERE t.id = :id
            "
        )
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->setParameter("id", $threadid);
        $query->useResultCache(true, 60, self::CACHE_THREAD_BYID . $threadid);
        return $query->getSingleResult();
    }

    public function findThreads($forumid, $pinned = false)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT t, u, f, p, u2
            FROM MaximModuleForumBundle:Thread t
            LEFT JOIN t.createdBy u
            JOIN t.forum f
            LEFT JOIN t.lastPost p
            LEFT JOIN t.lastPostCreator u2
            WHERE f.id = :id AND t.pinned = :pinned
            AND t.state = :state
            ORDER BY p.createdOn DESC"
        )
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->setParameter('pinned', $pinned)
            ->setParameter("state", Thread::THREAD_STATE_VISIBLE)
            ->setParameter('id', $forumid);
        $query->useResultCache(true, 60, __METHOD__ . serialize($query->getParameters()));

        return $query->getResult();
    }
}