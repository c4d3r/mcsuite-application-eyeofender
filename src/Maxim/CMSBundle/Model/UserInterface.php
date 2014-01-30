<?php
/**
 * Author: Maxim
 * Date: 28/01/14
 * Time: 14:44
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Model;


use Symfony\Component\Security\Core\User\AdvancedUserInterface;

interface UserInterface extends AdvancedUserInterface
{
    const GENDER_FEMALE  = 'f';
    const GENDER_MALE    = 'm';
    const GENDER_UNKNOWN = 'u';
} 