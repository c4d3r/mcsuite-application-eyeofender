<?php
/**
 * Author: Maxim
 * Date: 05/02/14
 * Time: 13:58
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class StaffHomepage extends Constraint
{
    public $notStaff = "You must be a staff member to post in this category";

    public function validatedBy()
    {
        return 'maxim_module_forum_validator_notstaffhomepage';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
} 