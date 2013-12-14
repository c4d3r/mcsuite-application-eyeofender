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
}