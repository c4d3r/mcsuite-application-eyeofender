<?php
/**
 * Author: Maxim
 * Date: 06/02/14
 * Time: 13:28
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Controller;


use Maxim\CMSBundle\Entity\Group;
use Maxim\CMSBundle\Entity\User;
use Maxim\Module\ForumBundle\Entity\ThreadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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

    public function banAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $logger = $this->get('logger');

        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN') || false === $this->get('security.context')->isGranted('ROLE_STAFF')) {
            throw new AccessDeniedException();
        }

        /**
         * @var User $user
         */
        $user = $em->getRepository('MaximCMSBundle:User')->findOneBy(array("id" => $id));
        if(!$user)
            return new Response(json_encode(array("success" => false, "message" => "Could not find user")));

        $group_banned = $em->getRepository('MaximCMSBundle:Group')->findOneBy(array("name" => "Banned"));
        if(!$group_banned)
            return new Response(json_encode(array("success" => false, "message" => "Could not find group with name: " . "Banned")));

        try
        {
            if($user->isBanned()) {
                $logger->err("Removing");
                $user->removeRank($group_banned);
            } else {
                $logger->err("Adding");
                $user->addRank($group_banned);
            }
            $em->flush();

            // clear cache
            $cacheDriver = $em->getConfiguration()->getResultCacheImpl();
            $cacheDriver->deleteAll();

            return new Response(json_encode(array("success" => true, "message" => $user->isBanned() ? "User has been banned succesfully" : "User has been Unbanned succesfully")));
        }
        catch(\Exception $ex)
        {
            $logger->err("[USER]" . $ex->getMessage());
            return new Response(json_encode(array("success" => false, "message" => "Could not ban user, please try again later")));
        }
    }
} 