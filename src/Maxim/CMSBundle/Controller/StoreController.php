<?php
namespace Maxim\CMSBundle\Controller;

use Maxim\CMSBundle\Entity\User;
use Maxim\CMSBundle\Event\StoreEvent;
use Maxim\CMSBundle\Event\UserEvent;
use Maxim\CMSBundle\Helper\RESTHelper;
use Maxim\CMSBundle\Listeners\StoreListener;
use Maxim\CMSBundle\Listeners\UserListener;
use Maxim\CMSBundle\StoreEvents;
use Maxim\CMSBundle\UserEvents;
use Payum\Core\Request\BinaryMaskStatusRequest;
use Payum\Paypal\ExpressCheckout\Nvp\Bridge\Buzz\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Yaml\Yaml;
use Maxim\CMSBundle\Entity\Purchase;
use Maxim\CMSBundle\Entity\StoreItem;
use Maxim\CMSBundle\Entity\PaypalExpressPaymentDetails;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Range;
use Payum\Paypal\ExpressCheckout\Nvp\Api;

class StoreController extends Controller
{
	public function indexAction()
	{
		# basic settings
        $donate_config  = $this->container->getParameter('maxim_cms.store');
    	$em = $this->getDoctrine()->getManager();
        $websiteid = $this->container->getParameter('website');

        $storeItems = $em->getRepository("MaximCMSBundle:StoreItem")->findAllVisibleOrderedByName($websiteid);
        $storeCategories = $em->getRepository("MaximCMSBundle:StoreCategory")->findAllVisibleOrderedBySort($websiteid);

        foreach($storeItems as $item)
        {
            if($item)
            {
                $data['items'][] = array(
                    "id"            => $item->getId(),
                    "name"          => $item->getName(),
                    "description"   => $item->getDescription(),
                    "amount"        => number_format(round($item->getAmount() * (1 -($item->getReduction() / 100)), 2), 2),
                    "image"         => $item->getImage(),
                    "category"       => $item->getStoreCategory(),
                );
            }
        }

        $data['topitems'] = $em->getRepository('MaximCMSBundle:Purchase')->findAllTopPurchases(5);
		$data['config'] = array("currency" => $donate_config['currency_symbol']);
        $data['categories'] = $storeCategories;

        return $this->render('MaximCMSBundle:pages:store/view.html.twig', $data);
	}
	
	public function step2Action(Request $request)
	{
		$em = $this->getDoctrine()->getManager();

        $id = $request->request->get('_btnBuy');

        $username 	= $request->request->get('_ign');

        return $this->render('MaximCMSBundle:pages:store/step2.html.twig', array(
            'item'      =>   $id,
            'ign'       =>   $username
        ));
	}

    public function confirmAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $donate_config  = $this->container->getParameter('maxim_cms.store');
        $id 		= $request->request->get('_previousChoice');
        $username 	= $request->request->get('_ign');
        //$terms      = $request->request->get('donation_terms');
        $item       = $em->getRepository("MaximCMSBundle:StoreItem")->findOneBy(array("id" => $id));
        $user = $this->getUser();

        if(!$item)
        {
            throw $this->createNotFoundException("Could not find the request item");
        }

        ###################
        # EVENT DISPATCHED
        ###################
       /* $dispatcher = $this->get('event_dispatcher');
        //add listeners
        $userListener  = new UserListener($em);

        $userEvent = new UserEvent($user);

        // dispatch update event
        $dispatcher->dispatch(UserEvents::USER_UPDATE, $userEvent);*/

        # check if purchase with this item has been made
        $today = new \DateTime("now");
        $purchase = $em->getRepository('MaximCMSBundle:Purchase')->findOneBy(array("name" => $username, "storeItem" => $item));

        if($purchase && ($purchase->getDate()->getTimestamp() + $item->getDuration() > $today->getTimestamp())){
            echo "set flashbag";
            $this->get('session')->getFlashBag()->add(
                'warning',
                sprintf("%s already has the item you are trying to buy.", $username)
            );
        }

        $custom = $username.'|'.date("Y-m-d H:i:s").'|'.$id.'|'.$_SERVER['REMOTE_ADDR'].'|'.$user->getEmail();

        $data['item'] =  array(
            "id"            => $item->getId(),
            "name"          => $item->getName(),
            "description"   => $item->getDescription(),
            "amount"        => number_format(round(($item->getAmount() * (1 -($item->getReduction() / 100))), 2), 2),
            "image"         => $item->getImage(),
            "category"      => $item->getStoreCategory(),
            "tax"           => $item->getTax()
        );

        $data['user'] = array(
            "username"              => $username,
            "custom"                => $custom,
            "ign"                   => $username
        );
        $data['config'] = $donate_config;

        return $this->render('MaximCMSBundle:pages:store/confirm.html.twig', $data);
    }

    public function prepareAction(User $user, $mcuser, StoreItem $item)
    {
        $paymentName = 'paypal_express_checkout_plus_doctrine';

        $data = array(
            "currency"  =>  "GBP",
        );
        $storage = $this->get('payum')->getStorageForClass(
            'Maxim\CMSBundle\Entity\PaymentDetails',
            $paymentName
        );

        $total = $item->getAmount() * 1;

        $tax = 0;
        if($item->getTax() > 0) {
            $tax = ($total * ($item->getTax() / 100));
        }

        /** @var $paymentDetails PaymentDetails */
        $paymentDetails = $storage->createModel();
        $paymentDetails['PAYMENTREQUEST_0_CURRENCYCODE'] = $data['currency'];
        $paymentDetails['PAYMENTREQUEST_0_ITEMAMT']      = number_format($total, 2);
        $paymentDetails['PAYMENTREQUEST_0_TAXAMT']       = number_format($tax, 2);
        $paymentDetails['PAYMENTREQUEST_0_AMT']          = number_format(($total + $tax), 2);
        //$paymentDetails['PAYMENTREQUEST_0_ITEMCATEGORY'] = Api::PAYMENTREQUEST_ITERMCATEGORY_PHYSICAL;
        //$paymentDetails['PAYMENTREQUEST_0_QTY']          = 1;
        //$paymentDetails['PAYMENTREQUEST_0_NAME']         = $item->getName();
        $paymentDetails['PAYMENTREQUEST_0_DESC']         = substr(strip_tags($item->getDescription()), 0, 126);

        $custom = array(
            "user_id" => $user->getId(),
            "amount"  => $total,
            "name"    => $mcuser,
            "ip"      => $user->getLastip(),
            "item_id" => $item->getId(),
            "discount" => $item->getReduction(),
        );
        $paymentDetails['PAYMENTREQUEST_0_CUSTOM'] = json_encode($custom);

        // DIGITAL ITEM
        $paymentDetails['L_PAYMENTREQUEST_0_NAME0'] =  strip_tags($item->getName());
        $paymentDetails['L_PAYMENTREQUEST_0_AMT0'] =  number_format($item->getAmount(), 2);
        $paymentDetails['L_PAYMENTREQUEST_0_QTY0'] =  1;
        $paymentDetails['L_PAYMENTREQUEST_0_DESC0'] =  substr(strip_tags($item->getDescription()), 0, 126);
        $paymentDetails['L_PAYMENTREQUEST_0_TAXAMT0'] = number_format($tax, 2);
        $paymentDetails['L_PAYMENTREQUEST_0_ITEMCATEGORY0'] = Api::PAYMENTREQUEST_ITERMCATEGORY_PHYSICAL;

        $storage->updateModel($paymentDetails);

        $notifyToken = $this->getTokenFactory()->createNotifyToken($paymentName, $paymentDetails);
        $captureToken = $this->getTokenFactory()->createCaptureToken(
            $paymentName,
            $paymentDetails,
            'paypal_done'
        );

        $paymentDetails['INVNUM']    = $paymentDetails->getId();
        $paymentDetails['RETURNURL'] = $captureToken->getTargetUrl();
        $paymentDetails['CANCELURL'] = $captureToken->getTargetUrl();
        $paymentDetails['PAYMENTREQUEST_0_NOTIFYURL'] = $notifyToken->getTargetUrl();


        $storage->updateModel($paymentDetails);
        return $this->redirect($captureToken->getTargetUrl());
    }

    public function finishAction(Request $request)
    {
        if (!('POST' === $request->getMethod()))
        {
            throw new AccessDeniedException("You can not access this area");
        }

        # parameters needed
        $em           = $this->getDoctrine();
        $forUsername  = $request->request->get('_ign');
        $custom       = explode('|', $request->request->get('custom'));

        # get item
        $item = $em->getRepository('MaximCMSBundle:StoreItem')->findOneBy(array("id" => $custom[2]));

        # check payment method
        $paymentmethod = $request->request->get('button_shop_checkout');

        switch(strtoupper($paymentmethod))
        {
            case "PAYPAL":
                return $this->prepareAction($this->getUser(), $forUsername, $item);
            case "BTC":
                return $this->paymentBitpay($this->getUser(), $forUsername, $item);
            default:
                return $this->prepareAction($this->getUser(), $forUsername, $item);
        }
    }

    public function paymentBitpay(User $user, $mcuser, StoreItem $item)
    {
        //https://bitpay.com/api/invoice

        # item price calculations
        $total = $item->getAmount() * 1;

        $tax = 0;
        if($item->getTax() > 0) {
            $tax = ($total * ($item->getTax() / 100));
        }

        # get custom field
        $custom = array(
            "user_id" => $user->getId(),
            "amount"  => $total,
            "name"    => $mcuser,
            "ip"      => $user->getLastip(),
            "item_id" => $item->getId(),
            "discount" => $item->getReduction(),
        );

        # start REST call
        $rest = $this->get('maxim_cms.rest.helper');
        $apiKey = "WV3IhdH0ysNsWSJtieYlLygwblFEJAXMo3tIWvH0I";
        $uname = base64_encode($apiKey);
        $data = array(
            "price" => $total,
            "currency" => "GBP",
            "notificationURL" => "http://google.com",
            "posData" => $custom
        );
        $data = json_encode($data);
        $length = strlen($data);
        $headers = array(
            'Content-Type: application/json',
            "Content-Length: $length",
            "Authorization: Basic $uname",
        );
        $rest->open("https://bitpay.com/api/invoice");
        $curl = $rest->getCurl();

        # set extra curls
        curl_setopt($curl, CURLOPT_PORT, 443);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC ) ;
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1); // verify certificate
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // check existence of CN and verify that it matches hostname
        curl_setopt($curl, CURLOPT_FORBID_REUSE, 1);
        curl_setopt($curl, CURLOPT_FRESH_CONNECT, 1);

        $holder = $rest->execute(RESTHelper::METHOD_POST, $headers, $data)->getData();
        # the data contains the array

        $holder = json_decode($holder, true);
        return new \Symfony\Component\HttpFoundation\Response($holder["url"] . "&view=iframe&theme=dark");
        //return $this->redirect("https://coinbase.com/checkouts/" . $holder['button']['code']);
    }

    public function completeAction(Request $request)
    {
        $logger = $this->get('logger');
        $token = $this->get('payum.security.http_request_verifier')->verify($request);
        $payment = $this->get('payum')->getPayment($token->getPaymentName());
        $request = Request::createFromGlobals();
        $session = $this->get('session');

        $status = new BinaryMaskStatusRequest($token);
        $payment->execute($status);

        if ($status->isSuccess())
        {
            $session->getFlashBag()->set(
                'notice',
                'Payment success.'
            );
        }
        else if ($status->isPending())
        {
            $session->getFlashBag()->set(
                'notice',
                'Payment is still pending. Credits were not added'
            );
        }
        else
        {
            $session->getFlashBag()->set('error', 'Payment failed');
        }

        return $this->redirect($this->generateUrl("home"));
    }

	public function purchaseHistoryAction()
	{
		$em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
		$user = $this->getUser();

		if($user)
		{
            $purchases = $qb->select('p')
                ->from('MaximCMSBundle:Purchase', 'p')
                ->innerJoin("MaximCMSBundle:User", "u", "WITH", "u.id = p.user")
                ->innerJoin("MaximCMSBundle:StoreItem", "s", "WITH", "p.storeItem = s.id")
                ->innerJoin('MaximCMSBundle:Website', 'w', 'WITH', 's.website = w.id')
                ->where($qb->expr()->notIn('p.status', array(Purchase::PURCHASE_FAILED, Purchase::PURCHASE_PENDING)))
                ->andWhere('w.id = :website')
                ->andWhere('u.id = :userid')
                ->setParameter('website', $this->container->getParameter('website'))
                ->setParameter(':userid', $user->getId())
                ->orderBy('p.date', 'DESC');

			$result['purchases'] = $purchases->getQuery()->getResult();
			return $this->render('MaximCMSBundle:Account:purchases.html.twig', $result);
		}
	}

    /**
     * @return TokenFactory
     */
    protected function getTokenFactory()
    {
        return $this->get('payum.security.token_factory');

    }

}
