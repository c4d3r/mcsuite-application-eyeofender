<?php

namespace Maxim\CMSBundle\Controller;

use Maxim\CMSBundle\Entity\UserNotification;
use Maxim\CMSBundle\Event\MinecraftSendEvent;
use Maxim\CMSBundle\Exception\CommandExecutionException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Maxim\CMSBundle\Controller\ModuleController;
use Maxim\CMSBundle\Controller\ShopController;
use Maxim\CMSBundle\Entity\StoreItem;
use Maxim\CMSBundle\Entity\Purchase;
use Maxim\CMSBundle\Entity\User;

class IpnController extends Controller
{
    public function makePurchase($amount, $transaction, \Maxim\CMSBundle\Entity\User $user, $item, $ip, $status, $date) {

        $em = $this->getDoctrine()->getManager();
        try
        {
            $purchase = new Purchase();
            $purchase->setAmount($amount);
            $purchase->setDate(new \DateTime(date("Y-m-d H:i:s", strtotime($date))));
            $purchase->setTransaction($transaction);
            $purchase->setStatus($status);
            $purchase->setIp($ip);
            $purchase->setUser($user);
            $purchase->setStoreItem($item);
            $purchase->setName($user->getUsername());
            $em->persist($purchase);
            $em->flush();
            return;
        }
        catch(\Exception $ex)
        {
            $logger = $this->get('logger');
            $logger->err('SHOP: '.$ex->getMessage());
        }
    }

    public function processItem($payment_amount, $storeItem, $name) {

        $em = $this->getDoctrine()->getManager();

        # get the amount from the item minus reduction
        $amount = $storeItem->getAmount() * (1 - ($storeItem->getReduction() / 100));

        $errors = array();
        $logger = $this->get('logger');

        if($amount == $payment_amount) {
            # send the item ingame
            $query = ModuleController::parseCommand(array("USER" => $name, "TIME" => time()), $storeItem->getCommand());

            $result = ModuleController::executeQuery($query);
            if(!$result['success']) {
                $errors[] = "[ITEM=" . $storeItem->getId() . "] Failed to execute: " . $result['message'];
            }

        } else {
            $errors[] = "Amount paid was not equal to the item price";
        }//end $amount == $payment_amount

        if(count($errors) > 0) {
            return array("success" => false, "message" => json_encode($errors));
        } else {
            return array("success" => true, "message" => "The item has been successfully");
        }
    } // end function

    public function generateBitpayHash($data, $key)
    {
        $hmac = base64_encode(hash_hmac('sha256', $data, $key, TRUE));
        return strtr($hmac, array('+' => '-', '/' => '_', '=' => ''));
    }
    public function bitpayIpnAction()
    {
        $details = file_get_contents("php://input");
        $apiKey = "WV3IhdH0ysNsWSJtieYlLygwblFEJAXMo3tIWvH0I";

        $json = json_decode($details, true);

        if (is_string($json))
            return new Response($json); // error

        if (!array_key_exists('posData', $json))
            return new Response('no posData');

        $posData = json_decode($json['posData'], true);

        if($posData['hash'] != $this->generateBitpayHash(serialize($posData['posData']), $apiKey))
            return new Response('authentication failed (bad hash)');

        if(is_array($details) && count($details) > 0 && isset($json['posData']))
        {
            $custom = $json['posData'];
            $custom = json_decode($custom, true);

            #set vars
            $mcUser   = $custom['name'];
            $userId   = $custom['user_id'];
            $itemId   = $custom['item_id'];
            $ip       = $custom['ip'];
            $discount = $custom['discount'];
            $transaction = $json['id'];
            $amountSupposed = $custom['price'];
            //$currency = $json['currency'];
            //$btcPrice = $json['btcPrice'];
            $amountReceived = $json['price'];

            $user = $this->doctrine->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $userId));
            $item = $this->doctrine->getRepository('MaximCMSBundle:StoreItem')->findOneBy(array("id" => $itemId));

            # address
            /*$address['address_street']       = $details['address_street']; //address + house number
            $address['address_country_code'] = $details['address_country_code'];  //country code
            $address['address_name']         = $details['address_name']; //address name (person)
            $address['address_country']      = $details['address_country']; //country
            $address['address_city']         = $details['address_city']; //address city
            $address['address_state']        = $details['address_state']; //address state
            $address['address_status']       = $details['address_status']; //addres status (confirmed ?)        */

            # create purchase
            $purchase = $this->purchaseHelper->createPurchase($item, $user, $amountReceived, $discount, Purchase::PURCHASE_PENDING, $mcUser, $ip, $transaction, Purchase::ITEM_DELIVERY_PENDING);
            $purchase->setMethod(Purchase::PAYMENT_METHOD_BITPAY);

            if($amountSupposed != $amountReceived) {
                $purchase->setStatus(Purchase::PURCHASE_INVALID_AMOUNT);
            }

            $succeeded = false;

            try
            {
                switch($item->getType()) {
                    case "COMMAND" :
                        $this->eventDispatcher->dispatch("minecraft.send", new MinecraftSendEvent(array($purchase)));
                        break;
                    default:
                        $options = array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION);
                        $pdo = new \PDO('mysql:host='.$this->config["host"].';dbname='.$this->config["database"], $this->config["username"], $this->config["password"], $options);
                        $pdo->query($this->minecraft->parseCommand($item->getCommand(), array("USER" => $purchase->getName())));
                        $pdo  = null;
                        $purchase->setStatus(Purchase::PURCHASE_COMPLETE);
                        $purchase->setItemDelivery(Purchase::ITEM_DELIVERY_SUCCESS);
                        $this->doctrine->flush();
                }
                $succeeded = true;
            }
            catch(\PDOException $ex)
            {
                $purchase->setStatus(Purchase::PURCHASE_ERROR_SQL);
                $purchase->setItemDelivery(Purchase::ITEM_DELIVERY_FAILED);
                $this->doctrine->flush();
            }
            catch(CommandExecutionException $ex)
            {
                $purchase->setStatus(Purchase::PURCHASE_ERROR_COMMAND);
                $purchase->setItemDelivery(Purchase::ITEM_DELIVERY_FAILED);
                $this->doctrine->flush();
            }
            catch(\Exception $ex)
            {
                $purchase->setStatus(Purchase::PURCHASE_ERROR_UNKNOWN);
                $purchase->setItemDelivery(Purchase::ITEM_DELIVERY_FAILED);
                $this->doctrine->flush();
            }

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
            $this->logger->err("PAYUM: custom field not found, " . print_r($details, true));
        }
    }

    public function complete($payment_method, $status, $email) {

        $logger = $this->get('logger');
        if($status['success'] == false) {
            $body = "We were unable to complete your payment, we have informed our staff team.<br/>Feel free to make a ticket on our website.";
            $logger->err("[STORE]" . print_r($status['message'], true));
        } else {
            $body = "Thank you for your purchase at mcthefridge.com";
        }

        try{
            $message = \Swift_Message::newInstance()
                ->setSubject("Your purchase at mcthefridge.com")
                ->setFrom($this->container->getParameter('maxim_cms.emails.info'))
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'MaximCMSBundle:email:plain.html.twig', array("message" => $body)
                    )
                )
                ->setContentType("text/html")
            ;
            $this->get('mailer')->send($message);

        }catch(\Exception $ex) {
            $logger->err("[STORE] Could not send mail: " . $ex->getMessage());
        }

    }
}
