<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 04/09/13
 * Time: 14:45
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Listeners;

use Maxim\CMSBundle\Event\StoreEvent;
use Maxim\CMSBundle\StoreEvents;
use Monolog\Logger;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StoreListener {

    protected $container;
    protected $logger;
    protected $token_factory;

    public function onOrderConfirm(StoreEvent $event)
    {
        $response = $event->getResponse();
        $request  = $event->getRequest();
    }

    public function __construct(ContainerInterface $container, Logger $logger, $tokenFactory)
    {
        $this->container = $container;
        $this->logger = $logger;
        $this->token_factory = $tokenFactory;
    }

    public function onOrderValidate(StoreEvent $event)
    {
        $errors = array();
        $username = $event->getUsername();
        $terms    = $event->getTerms();

        if(empty($username))
        {
            $errors[] = 'Please choose a username';
            $event->setIsValid(false);
        }
        else if(!isset($terms))
        {
            $errors[] = 'Please Accept our terms';
            $event->setIsValid(false);
        }

        if(!$event->getIsValid())
        {
            $event->setErrors($errors);
            $event->getDispatcher()->dispatch(StoreEvents::STORE_VALIDATE_FAILURE, $event);
        }
    }

    public function onOrderValidateFailure(StoreEvent $event)
    {

    }

    public function onOrderCancel(StoreEvent $event)
    {

    }

    public function onOrderSuccess(StoreEvent $event)
    {

    }

    public function onOrderFailed(StoreEvent $event)
    {

    }
    public function onStorePaymentSuccess(StoreEvent $event)
    {

    }
    public function onStorePay(StoreEvent $event)
    {
        ###############################
        # FORWARD TO PAYMENT PROCESSOR
        ###############################
        $paymentName = 'paypal_express_checkout_plus_doctrine';

        $item = $event->getPurchase()->getStoreItem();

        $data = array(
            "currency"  =>  "GBP",
        );
        $storage = $this->container->get('payum')->getStorageForClass(
            'Maxim\CMSBundle\Entity\PaypalExpressPaymentDetails',
            $paymentName
        );
        /** @var $paymentDetails PaymentDetails */
        $paymentDetails = $storage->createModel();
        $paymentDetails->setPaymentrequestCurrencycode(0, $data['currency']);
        $paymentDetails->setPaymentrequestAmt(0,  $item->getAmount() * 1);
        //$paymentDetails->setNoshipping(Api::NOSHIPPING_NOT_DISPLAY_ADDRESS);
        //$paymentDetails->setReqconfirmshipping(Api::REQCONFIRMSHIPPING_NOT_REQUIRED);
        $paymentDetails->setLPaymentrequestItemcategory(0, 0, Api::PAYMENTREQUEST_ITERMCATEGORY_PHYSICAL);
        $paymentDetails->setLPaymentrequestAmt(0, 0, $item->getAmount());
        $paymentDetails->setLPaymentrequestQty(0, 0, 1);
        $paymentDetails->setLPaymentrequestName(0, 0, $item->getName());
        $paymentDetails->setLPaymentrequestDesc(0, 0, htmlentities($item->getDescription()));
        $paymentDetails->setPaymentrequestCustom(0, $event->getPurchase()->getId());
        $storage->updateModel($paymentDetails);
        $notifyToken = $this->token_factory->createNotifyToken($paymentName, $paymentDetails);
        $captureToken = $this->token_factory->createCaptureToken(
            $paymentName,
            $paymentDetails,
            'paypal_done'
        );
        $paymentDetails->setReturnurl($captureToken->getTargetUrl());
        $paymentDetails->setCancelurl($captureToken->getTargetUrl());
        $paymentDetails->setPaymentrequestNotifyurl(0, $notifyToken->getTargetUrl());
        $paymentDetails->setInvnum($paymentDetails->getId());
        $storage->updateModel($paymentDetails);
        $event->setCaptureToken($captureToken->getTargetUrl());
        return;
    }

    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->container->get('payum');
    }
}