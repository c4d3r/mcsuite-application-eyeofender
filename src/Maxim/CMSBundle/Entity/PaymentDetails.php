<?php

namespace Maxim\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Payum\Core\Model\ArrayObject;

class PaymentDetails extends ArrayObject
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}