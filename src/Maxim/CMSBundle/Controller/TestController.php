<?php

namespace Maxim\CMSBundle\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Maxim\CMSBundle\Entity\News;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Maxim\CMSBundle\Entity\Useraccounts;
use Maxim\CMSBundle\Entity\StoreItem;
use Maxim\CMSBundle\Entity\Purchase;
use Maxim\CMSBundle\Controller\IpnController;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Maxim\CMSBundle\Entity\PaypalExpressPaymentDetails;
use Payum\Request\BinaryMaskStatusRequest;
use Symfony\Component\Validator\Constraints\Range;

use Sensio\Bundle\FrameworkExtraBundle\Configuration as Extra;

use Payum\Registry\RegistryInterface;
use Payum\Paypal\ExpressCheckout\Nvp\Api;
use Payum\Paypal\ExpressCheckout\Nvp\Model\PaymentDetails;
use Payum\Bundle\PayumBundle\Service\TokenManager;
class TestController extends Controller {
    public function viewAction(Request $request)
    {
        $token = $this->getTokenManager()->getTokenFromRequest($request);

        $payment = $this->getPayum()->getPayment($token->getPaymentName());

        try {
            $payment->execute(new SyncRequest($token));
        } catch (RequestNotSupportedException $e) {}

        $status = new BinaryMaskStatusRequest($token);
        $payment->execute($status);

        return $this->render('AcmePaymentBundle:Details:view.html.twig', array(
            'status' => $status,
            'paymentTitle' => ucwords(str_replace(array('_', '-'), ' ', $token->getPaymentName()))
        ));
    }

    public function prepareDoctrineAction(Request $request)
    {
        $paymentName = 'paypal_express_checkout_plus_doctrine';

        $form = $this->createPurchaseForm();
        if ('POST' === $request->getMethod()) {
            $form->bind($request);
            if ($form->isValid()) {
                $data = $form->getData();

                $storage = $this->getPayum()->getStorageForClass(
                    'Maxim\CMSBundle\Entity\PaypalExpressPaymentDetails',
                    $paymentName
                );

                /** @var $paymentDetails PaymentDetails */
                $paymentDetails = $storage->createModel();
                $paymentDetails->setPaymentrequestCurrencycode(0, $data['currency']);
                $paymentDetails->setPaymentrequestAmt(0,  $data['amount']);

                $storage->updateModel($paymentDetails);

                $captureToken = $this->getTokenManager()->createTokenForCaptureRoute(
                    $paymentName,
                    $paymentDetails,
                    'acme_payment_details_view'
                );

                $paymentDetails->setReturnurl($captureToken->getTargetUrl());
                $paymentDetails->setCancelurl($captureToken->getTargetUrl());
                $paymentDetails->setInvnum($paymentDetails->getId());
                $storage->updateModel($paymentDetails);

                return $this->redirect($captureToken->getTargetUrl());
            }
        }

        return $this->render('MaximCMSBundle:test:prepare.html.twig', array(
            'form' => $form->createView(),
            'paymentName' => $paymentName
        ));

    }
    /**
     * @return RegistryInterface
     */
    protected function getPayum()
    {
        return $this->get('payum');
    }

    /**
     * @return TokenManager
     */
    protected function getTokenManager()
    {
        return $this->get('payum.token_manager');
    }

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
