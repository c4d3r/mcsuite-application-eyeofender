<?php

namespace Maxim\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpKernel\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Maxim\CMSBundle\Controller\ModuleController;
use Maxim\CMSBundle\Controller\ShopController;
use Maxim\CMSBundle\Entity\Shop;
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
            $purchase->setShop($item);
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

    public function processItem($payment_amount, $shop, $name) {

        $em = $this->getDoctrine()->getManager();

        # get the amount from the item minus reduction
        $amount = $shop->getAmount() * (1 - ($shop->getReduction() / 100));

        $errors = array();
        $logger = $this->get('logger');

        if($amount == $payment_amount) {
            # send the item ingame
            $query = ModuleController::parseCommand(array("USER" => $name, "TIME" => time()), $shop->getCommand());

            $result = ModuleController::executeQuery($query);
            if(!$result['success']) {
                $errors[] = "[ITEM=" . $shop->getId() . "] Failed to execute: " . $result['message'];
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

    public function complete($payment_method, $status, $email) {

        $logger = $this->get('logger');
        if($status['success'] == false) {
            $body = "We were unable to complete your payment, we have informed our staff team.<br/>Feel free to make a ticket on our website.";
            $logger->err("[SHOP]" . print_r($status['message'], true));
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
            $logger->err("[SHOP] Could not send mail: " . $ex->getMessage());
        }

    }
}
