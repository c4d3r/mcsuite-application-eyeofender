<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 01/09/13
 * Time: 09:23
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Action;
use Doctrine\ORM\EntityManager;
use Maxim\CMSBundle\Entity\Notification;
use Maxim\CMSBundle\Entity\NotificationDetails;
use Maxim\CMSBundle\Entity\UserNotification;
use Maxim\CMSBundle\Event\MinecraftSendEvent;
use Maxim\CMSBundle\Exception\CommandExecutionException;
use Maxim\CMSBundle\Helper\DeliverHelper;
use Maxim\CMSBundle\Helper\MinecraftHelper;
use Maxim\CMSBundle\Helper\PurchaseHelper;
use Monolog\Logger;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\CaptureRequest;
use Payum\Core\Request\SecuredNotifyRequest;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Maxim\CMSBundle\Entity\Purchase;

class StoreNotificationAction implements ActionInterface
{
    protected $doctrine;
    protected $logger;
    protected $purchaseHelper;
    protected $eventDispatcher;
    protected $deliverHelper;

    public function __construct(EntityManager $doctrine, Logger $logger, PurchaseHelper $purchaseHelper, $eventDispatcher, DeliverHelper $deliverHelper) {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->purchaseHelper = $purchaseHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->deliverHelper = $deliverHelper;
    }

    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /** @var NotifyTokenizedDetailsRequest $request */
        $notification = new NotificationDetails;
        $notification->setPaymentName($request->getToken()->getPaymentName());
        $notification->setDetails($request->getNotification());
        $notification->setCreatedAt(new \DateTime);
        $this->doctrine->persist($notification);
        $this->doctrine->flush();

        $details = $notification->getDetails();

        if(is_array($details) && count($details) > 0 && isset($details['custom']))
        {
            $custom = $details['custom'];
            $custom = json_decode($custom, true);

            #set vars
            $mcUser   = $custom['name'];
            $userId   = $custom['user_id'];
            $itemId   = $custom['item_id'];
            $ip       = $custom['ip'];
            $discount = $custom['discount'];
            $transaction = $details['txn_id'];
            $amountSupposed = $custom['amount'];
            $amountReceived = $details['mc_gross'];

            $user = $this->doctrine->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $userId));
            $item = $this->doctrine->getRepository('MaximCMSBundle:StoreItem')->findOneBy(array("id" => $itemId));

            # address
            $address['address_street']       = $details['address_street']; //address + house number
            $address['address_country_code'] = $details['address_country_code'];  //country code
            $address['address_name']         = $details['address_name']; //address name (person)
            $address['address_country']      = $details['address_country']; //country
            $address['address_city']         = $details['address_city']; //address city
            $address['address_state']        = $details['address_state']; //address state
            $address['address_status']       = $details['address_status']; //addres status (confirmed ?)

            # create purchase
            //public function createPurchase($item, User $user, $amount, $discount, $status, $name, $ip, $transaction, $delivery) {
            $purchase = $this->purchaseHelper->createPurchase($item, $user, $amountReceived, $discount, Purchase::PURCHASE_PENDING, $mcUser, $ip, $transaction, Purchase::ITEM_DELIVERY_PENDING);
            $purchase->setMethod(Purchase::PAYMENT_METHOD_PAYPAL);

            if($amountSupposed != $amountReceived) {
                $purchase->setStatus(Purchase::PURCHASE_INVALID_AMOUNT);
            }

            # deliver the item
            $succeeded = $this->deliverHelper->deliver($purchase);

            # Create notification
            $notification = new UserNotification();
            $notification->setType(UserNotification::TYPE_PURCHASE);
            $notification->setReceiver($purchase->getUser());
            $notification->setData(array(
                'order'  =>  $purchase->getId(),
                'status' =>  $succeeded ? "successful" : "unsuccessful"
            ));
            $this->doctrine->persist($notification);
            $this->doctrine->flush();

            $this->logger->info("Payment " . $succeeded ? "completed" : "failed" . " for user: " . $purchase->getUser()->getUsername());

        } else {
            $this->logger->error("PAYUM: custom field not found, " . print_r($details, true));
        }
    }

    public function supports($request)
    {
        return $request instanceof SecuredNotifyRequest;
    }
}