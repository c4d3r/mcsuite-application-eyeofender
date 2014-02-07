<?php
/**
 * Author: Maxim
 * Date: 15/11/13
 * Time: 10:47
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class ForumRepository extends EntityRepository
{

     public function findAllForums()
     {
         $query = $this->getEntityManager()->createQuery(
             "SELECT f, c, u
             FROM MaximModuleForumBundle:Forum f
             LEFT JOIN f.category c
             LEFT JOIN f.createdBy u
             "
         )->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);

         $query->useResultCache(true, 60, __METHOD__ . serialize($query->getParameters()));
         return $query->getResult();

     }


}