<?php
/**
 * Author: Maxim
 * Date: 06/02/14
 * Time: 13:28
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends Controller
{
    public function resendPurchaseAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $purchaseid = $request->request->get('_purchaseid');

        $purchase = $em->getRepository('MaximCMSBundle:Purchase')->findOneBy(array("id" => $purchaseid));
        if(!$purchase) {
            return new Response(json_encode(array("message" => "cannot find purchase")));
        }

        $deliverHelper = $this->get('maxim_cms.deliver.helper');
        $deliverHelper->deliver($purchase);

        return new Response(json_encode(array("store_item_delivery" => $purchase->getItemDelivery(), "status" => $purchase->getStatus())));
    }
} 