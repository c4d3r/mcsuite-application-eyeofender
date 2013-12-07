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
use Maxim\CMSBundle\Event\MinecraftSendEvent;
use Maxim\CMSBundle\Exception\CommandExecutionException;
use Maxim\CMSBundle\Exception\QueryException;
use Maxim\CMSBundle\Helper\PurchaseHelper;
use Maxim\CMSBundle\Listeners\MinecraftSendListener;
use Monolog\Logger;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\CaptureRequest;
use Payum\Core\Request\NotifyTokenizedDetailsRequest;
use Payum\Core\Request\SecuredNotifyRequest;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Maxim\CMSBundle\Entity\Visitor;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Maxim\CMSBundle\Entity\Purchase;
class StoreNotificationAction implements ActionInterface
{
    protected $doctrine;
    protected $logger;
    protected $purchaseHelper;
    protected $eventDispatcher;

    protected $config;

    public function __construct(EntityManager $doctrine, Logger $logger, PurchaseHelper $purchaseHelper, $eventDispatcher, $minecraft, $config) {
        $this->doctrine = $doctrine;
        $this->logger = $logger;
        $this->purchaseHelper = $purchaseHelper;
        $this->eventDispatcher = $eventDispatcher;
        $this->minecraft = $minecraft;

        $this->config = $config;

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
            $purchaseId = $details['custom'];
            $purchase = $this->doctrine->getRepository('MaximCMSBundle:Purchase')->findOneBy(array("id" => $purchaseId));
            $item = $purchase->getShop();

            # set transaction id
            $purchase->setTransaction($details['txn_id']);

            # address
            $address['address_street']       = $details['address_street']; //address + house number
            $address['address_country_code'] = $details['address_country_code'];  //country code
            $address['address_name']         = $details['address_name']; //address name (person)
            $address['address_country']      = $details['address_country']; //country
            $address['address_city']         = $details['address_city']; //address city
            $address['address_state']        = $details['address_state']; //address state
            $address['address_status']       = $details['address_status']; //addres status (confirmed ?)

            # set amount paid
            $purchase->setAmount($details['mc_gross']);

            # event dispatcher
            $dispatcher = $this->eventDispatcher;

            $succeeded = false;

            try
            {
                switch($item->getType()) {
                    case "COMMAND" :
                        $dispatcher->dispatch("minecraft.send", new MinecraftSendEvent(array(0 => $this->minecraft->parseCommand($item->getCommand, array("USER" => $purchase->getName())))));
                        $purchase->setStatus(Purchase::PURCHASE_COMPLETE);
                        break;
                    default:
                        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
                        $pdo = new \PDO('mysql:host='.$this->config["host"].';dbname='.$this->config["database"], $this->config["username"], $this->config["password"], $options);
                        $pdo->query($this->minecraft->parseCommand($item->getCommand(), array("USER" => $purchase->getName())));
                        $pdo  = null;
                        $purchase->setStatus(Purchase::PURCHASE_COMPLETE);
                }
                $succeeded = true;
            }
            catch(QueryException $ex)
            {
                $purchase->setStatus(Purchase::PURCHASE_ERROR_SQL);
            }
            catch(CommandExecutionException $ex)
            {
                $purchase->setStatus(Purchase::PURCHASE_ERROR_COMMAND);
            }
            catch(\Exception $ex)
            {
                $purchase->setStatus(Purchase::PURCHASE_ERROR_UNKNOWN);
            }

            # Create notification
            $notification = new Notification();
            $notification->setType(Notification::TYPE_PURCHASE);
            $notification->setUser($purchase->getUser());
            $notification->setText(sprintf("Payment for order %s was %s", "#".$purchase->getId(), $succeeded ? "successful" : "unsuccessful"));
            $this->doctrine->persist($notification);
            $this->doctrine->flush();

            $this->logger->info("Payment " . $succeeded ? "completed" : "failed" . " for user: " . $purchase->getUser()->getUsername());

        } else {
            $this->logger->err("PAYUM: custom field not found, " . print_r($details, true));
        }
    }

    public function supports($request)
    {
        return $request instanceof SecuredNotifyRequest;
    }
}