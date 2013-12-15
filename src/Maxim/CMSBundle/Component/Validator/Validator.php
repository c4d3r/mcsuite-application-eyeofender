<?php
/**
 * Author: Maxim
 * Date: 15/12/13
 * Time: 10:15
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Component\Validator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Validator\ExecutionContextInterface;

class Validator
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function validateSpam($object, ExecutionContextInterface $context)
    {
        // TODO: come up with a decent way to verify against a database
    }

    public function validateAdvertising($object, ExecutionContextInterface $context)
    {
        //TODO: same
    }
} 