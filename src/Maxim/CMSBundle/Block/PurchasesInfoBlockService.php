<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 13:47
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

class PurchasesInfoBlockService extends BaseBlockService
{
    protected $purchaseManager;
    protected $configs;

    public function getName()
    {
        return "Purchases info";
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

        $total_amount = $this->purchaseManager->findTotalAmountEarnedThisMonth();

        $totals = array(
            "amount"    => ($total_amount['amount'] == null) ? 0 : $total_amount['amount'],
        );

        return $this->renderResponse($blockContext->getTemplate(), array(
            // 'feeds'     => $feeds,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings,
            'totals' => $totals,
            'currency_symbol' => $this->configs['currency_symbol']
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'url'      => false,
            'title'    => 'Purchase info',
            'template' => 'MaximCMSBundle:Admin:Block/purchases_info.html.twig',
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