<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\Token;

/**
 * @ORM\Table(name="payum_security_token")
 * @ORM\Entity
 */
class PayumSecurityToken extends Token
{

}