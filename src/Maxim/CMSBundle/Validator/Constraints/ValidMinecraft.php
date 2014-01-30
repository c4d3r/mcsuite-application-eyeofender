<?php
/**
 * Author: Maxim
 * Date: 30/01/14
 * Time: 21:50
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Validator\Constraints;


use Symfony\Component\Validator\Constraint;

class ValidMinecraft extends Constraint
{
    public $useMinecraftUsernameMessage = "Please use your minecraft username as the website username";
    public $customMinecraftMessage = "Minecraft.net - %string%";

    public function validatedBy()
    {
        return 'maxim_cms_validator_minecraft';
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
} 