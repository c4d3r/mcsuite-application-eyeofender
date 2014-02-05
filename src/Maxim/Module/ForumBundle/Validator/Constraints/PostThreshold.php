<?php
/**
 * Author: Maxim
 * Date: 30/01/14
 * Time: 21:50
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class PostThreshold extends Constraint
{
    public $postThresholdMessage = "Please wait %time% %string_minutes% before posting again";

    public function validatedBy()
    {
        return 'maxim_module_forum_validator_postThreshold';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
} 