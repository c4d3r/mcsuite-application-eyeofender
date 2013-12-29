<?php
/**
 * Author: Maxim
 * Date: 29/12/13
 * Time: 18:43
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Block;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\CoreBundle\Entity\ManagerInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Validator\ErrorElement;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\BlockBundle\Block\BaseBlockService;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Maxim\CMSBundle\Entity\PurchaseManager;

class PurchasesBlockService extends BaseBlockService
{
    protected $purchaseManager;
    protected $configs;

    public function getName()
    {
        return "Purchase graph";
    }

    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('url', 'url', array('required' => false)),
                array('title', 'text', array('required' => false)),
            )
        ));
    }

    public function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
    }

    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        // merge settings
        $settings = $blockContext->getSettings();

        $purchases = $this->purchaseManager->findPurchasesPerDay();
        $orders    = $this->purchaseManager->findTotalAmountEarnedThisMonth();

        $purchaseData = array();
        foreach($purchases as $purchase)
        {
            $purchaseData[] = array_values($purchase);
        }

        return $this->renderResponse($blockContext->getTemplate(), array(
            // 'feeds'     => $feeds,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings,
            'purchaseData' => json_encode($purchaseData),
            'currMonth' => date("F"),
            'orders'=> array("total" => $orders["total"], "total_amount" => $orders["amount"]),
            'store' => $this->configs
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'url'      => false,
            'title'    => 'Purchases this month',
            'template' => 'MaximCMSBundle:Admin:Block/purchases.html.twig',
        ));
    }

    public function setPurchaseManager(PurchaseManager $purchaseManager)
    {
        $this->purchaseManager = $purchaseManager;
    }
    public function setConfigs($configs)
    {
        $this->configs = $configs;
    }
} 