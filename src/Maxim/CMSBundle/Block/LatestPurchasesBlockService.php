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

class LatestPurchasesBlockService extends BaseBlockService
{
    protected $purchaseManager;

    public function getName()
    {
        return "Latest purchases";
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

        $purchases = $this->purchaseManager->findLatestPurchases(10);

        return $this->renderResponse($blockContext->getTemplate(), array(
            // 'feeds'     => $feeds,
            'block'     => $blockContext->getBlock(),
            'settings'  => $settings,
            'latestPurchases' => $purchases
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultSettings(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'url'      => false,
            'title'    => 'Latest Purchases',
            'template' => 'MaximCMSBundle:Admin:Block/latest_purchases.html.twig',
        ));
    }

    public function setPurchaseManager(PurchaseManager $purchaseManager)
    {
        $this->purchaseManager = $purchaseManager;
    }
} 