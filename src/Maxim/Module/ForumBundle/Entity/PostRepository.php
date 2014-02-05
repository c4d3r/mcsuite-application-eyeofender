<?php
/**
 * Author: Maxim
 * Date: 11/11/13
 * Time: 14:50
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class PostRepository extends EntityRepository
{
    public function findLatestPost($user)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT p
            FROM MaximModuleForumBundle:Post p
            JOIN p.createdBy u
            WHERE u.id = :user
            ORDER BY p.createdOn DESC"
        );
        $query->setParameter("user", $user->getId());
        $query->setMaxResults(1);
        return $query->getResult();
    }

    public function findLatestPosts($amount = 10, $websiteid)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT p, u, t
            FROM MaximModuleForumBundle:Post p
            INNER JOIN p.createdBy u
            LEFT JOIN p.thread t
            INNER JOIN t.forum f
            INNER JOIN f.category c
            WHERE c.website = :websiteid
            ORDER BY p.createdOn DESC
        ");
        $query->setParameter('websiteid', $websiteid);
        $query->setMaxResults($amount);
        $query->setHint(Query::HYDRATE_OBJECT, true);

        return $query->getResult();

    }

    public function findThreadPosts($threadid)
    {
        $query = $this->getEntityManager()->createQuery(
            "SELECT p, u, l, g
            FROM MaximModuleForumBundle:Post p
            JOIN p.createdBy u
            LEFT JOIN u.groups g
            JOIN p.thread t
            LEFT JOIN p.likes l
            WHERE t.id = :id
            "
        )
            ->setParameter("id", $threadid)
            ->setHint(Query::HYDRATE_OBJECT, true)
        ;
        return $query->getResult();
    }
}