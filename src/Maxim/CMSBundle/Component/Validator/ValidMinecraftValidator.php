<?php
/**
 * Author: Maxim
 * Date: 30/01/14
 * Time: 21:28
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Component\Validator;

use Maxim\CMSBundle\Entity\User;
use Maxim\CMSBundle\Helper\MinecraftHelper;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\ExecutionContextInterface;

class ValidMinecraftValidator extends ConstraintValidator
{
    private $minecraftHelper;

    public function validate($object, Constraint $constraint)
    {
        $verified = $this->minecraftHelper->signIn($object->getMcUsername(), $object->getMcPassword());


        if(!$verified['success'])
        {
            # return violation
            $this->context->addViolation($constraint->customMinecraftMessage, array('%string%' => $verified['message']));
        }
        else if(strtoupper(trim($verified['account']["name"])) != strtoupper(trim($object->getUsername())))
        {
            # return violation
            $this->context->addViolationAt('mcUsername', $constraint->useMinecraftUsernameMessage, array(), null);
        }
    }

    public function setMinecraftHelper(MinecraftHelper $minecraftHelper)
    {
        $this->minecraftHelper = $minecraftHelper;
    }

    public function validatedBy()
    {
        return 'maxim_cms_validator_user';
    }
} 