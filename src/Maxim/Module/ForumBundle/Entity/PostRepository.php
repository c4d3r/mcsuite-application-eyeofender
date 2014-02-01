<?php
/**
 * Author: Maxim
 * Date: 11/11/13
 * Time: 14:50
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;


use Doctrine\ORM\EntityRepository;

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
            "SELECT p, u, t, f
            FROM MaximModuleForumBundle:Post p
            JOIN p.createdBy u
            JOIN p.thread t
            JOIN t.forum f
            JOIN f.category c
            WHERE c.website = :websiteid
            ORDER BY p.createdOn DESC
        ");

        $query->setParameter('websiteid', $websiteid);
        $query->setMaxResults($amount);
        return $query->getResult();

    }
}