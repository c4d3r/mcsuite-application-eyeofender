<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 30/08/13
 * Time: 20:15
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Model;

use Payum\Paypal\ProCheckout\Nvp\Model\PaymentDetails;

class PaypalProPaymentDetails extends PaymentDetails
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }
}