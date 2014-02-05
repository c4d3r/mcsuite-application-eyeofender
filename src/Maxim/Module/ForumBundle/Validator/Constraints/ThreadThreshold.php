<?php
/**
 * Author: Maxim
 * Date: 30/01/14
 * Time: 21:50
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class ThreadThreshold extends Constraint
{
    public $threadThresholdMessage = "Please wait %time% %string_minutes% before posting a new thread";

    public function validatedBy()
    {
        return 'maxim_module_forum_validator_threadThreshold';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
} 