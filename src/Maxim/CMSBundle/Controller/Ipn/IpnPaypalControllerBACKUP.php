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

class IpnPaypalController extends IpnController{
    public function paypalAction()
    {
        $logger = $this->get('logger');
        $config = $this->container->getParameter('maxim_cms');
        $donate_config  = $this->container->getParameter('maxim_cms.shop');

        //$core->Log('ipn', 'called');
        // read the post from PayPal system and add 'cmd'
        // Read the POST from PayPal system and build validation request string.
        $validate_request = 'cmd=_notify-validate';
        foreach ($_POST as $key => $value)
        {
            $validate_request .= "&" . $key . "=" . urlencode(stripslashes($value));
        }

        $url = $donate_config['paypal'];
        $curl_result = '';
        $curl_err = '';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $donate_config['paypal']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $validate_request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($validate_request)));
        curl_setopt($ch, CURLOPT_HEADER , 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $curl_result = curl_exec($ch);
        $curl_err = curl_error($ch);
        curl_close($ch);
        // assign posted variables to local variables
        $payment_status     = $_POST['payment_status'];
        $payer_email        = $_POST['payer_email'];
        $transaction_id     = $_POST['txn_id'];
        $payment_amount     = $_POST['mc_gross'];
        $payment_currency   = $_POST['mc_currency'];
        $receiver_email     = $_POST['receiver_email'];
        $payer_name         = $_POST['first_name']." ".$_POST['last_name'];
        $custom             = $_POST['custom'];
        $custom             = explode('|', $custom);

        $errors = array();
        $status = array("success" => true);

        if(!(count($custom) > 3)) {
            $status = array("success" => false);
            $errors[] = "The custom field was manipulated, #VARIABLES: " . count($custom);
        }

        if(!(strpos($curl_result, "VERIFIED")!==false)) {
            $status = array("success" => false);
            $errors[] = "The payment was incorrect, CODE: " . strpos($curl_result, "VERIFIED");
        }

        if(!(strtoupper($payment_status) == 'COMPLETED'))
        {
            $status = array("success" => false);
            $errors[] = "The status was not completed, CODE: " . strtoupper($payment_status);
        }

        if(!(strtoupper($receiver_email) == strtoupper($donate_config['email']))) {
            $errors[] = "The receiver email was incorrect, GOT: " . strtoupper($receiver_email) . " must be: " . strtoupper($donate_config['email']);
            $status = array("success" => false);
        }

        if(!($payment_amount > 0)) {
            $errors[] = "The amount paid was incorrect or not equal to the item price";
            $status = array("success" => false);
        }

        if($status['success']) {
            $logger->err('IPN: SUCCESS');
            $email  = $custom[4];
            $em = $this->getDoctrine()->getManager();

            # get the item object from the shop
            $shop = $em->getRepository('MaximCMSBundle:Shop')->findOneBy(array("id" => $custom[2]));
            if(!$shop) { $status = array("success" => false, "message" => "Could not find the request item"); }

            # get the user that paid
            $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("email" => $email));
            if(!$shop) { $status = array("success" => false, "message" => "Could not find the request item"); }

            // public function makePurchase($amount, $transaction, $user, $item, $ip, $status, $date) {
            try {
                $result = $this->processItem($payment_amount, $shop, $custom[0]);
            } catch(\Exception $ex) {
                  $result  = false;
            }

            $this->makePurchase($payment_amount, $transaction_id, $user, $shop, $custom[3], ($result['success'] ? "Success" : "Failed"), $custom[1]);
            if(!$result['success']){ $status = $result; }

        } else {
            $logger->err('IPN: ' . print_r($errors, true));
            $status = array("success" => false, "message" => json_encode($errors));
        }

        $this->complete("paypal", $status, $email);
        return new Response(json_encode($status));
    }
}