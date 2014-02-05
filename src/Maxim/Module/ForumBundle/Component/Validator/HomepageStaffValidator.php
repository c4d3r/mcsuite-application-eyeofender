<?php
/**
 * Author: Maxim
 * Date: 05/02/14
 * Time: 13:59
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Component\Validator;


use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class HomepageStaffValidator extends ConstraintValidator
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function validate($object, Constraint $constraint)
    {
        if($object->getForum()->getShowOnHome() == true && !$this->securityContext->isGranted('ROLE_STAFF'))
        {
            $this->context->addViolation($constraint->notStaff);
        }
    }

    public function validatedBy()
    {
        return 'maxim_module_forum_validator_notstaffhomepage';
    }
} 