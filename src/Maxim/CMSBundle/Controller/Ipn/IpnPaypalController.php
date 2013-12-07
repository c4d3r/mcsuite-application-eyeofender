<?php
/**
 * Project: MCSuite
 * File: IpnPaypalController.php
 * User: Maxim
 * Date: 07/06/13
 * Time: 14:06
 */

namespace Maxim\CMSBundle\Controller\Ipn;

use Maxim\CMSBundle\Controller\IpnController;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Maxim\CMSBundle\Entity\PaypalExpressPaymentDetails;
use Payum\Request\BinaryMaskStatusRequest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Constraints\Range;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;

use Payum\Registry\RegistryInterface;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Paypal\ExpressCheckout\Nvp\Model\PaymentDetails;
use Payum\Bundle\PayumBundle\Service\TokenManager;
class IpnPaypalController extends IpnController{


    private $clientId = "AbyJoRCgqi--pWgKaqInswDd7u-pLgaZ1a37wa8v1tk0c4A-V9aJMEbEuMug";
    private $clientSecret = "EPp5mRBchRZFhyvkT83zv40LldFS5mhnVJzegPZ9uWGV65LOOu39sMADgZq1";
    private $token;
    private $host = 'https://api.sandbox.paypal.com';

    /**
     * @return \Symfony\Component\Form\Form
     */
    protected function createPurchaseForm()
    {
        return $this->createFormBuilder()
            ->add('amount', null, array(
                'data' => 1,
                'constraints' => array(new Range(array('max' => 2)))
            ))
            ->add('currency', null, array('data' => 'USD'))

            ->getForm()
            ;
    }

}