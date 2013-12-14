<?php
/**
 * Author: Maxim
 * Date: 13/12/13
 * Time: 15:10
 * Property of MCSuite
 */

namespace Maxim\Manager;


class UserManager
{
    protected $doctrine;

    public function __construct(EntityManager $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function findUsers()
    {
        return $this->doctrine->
    }
} 