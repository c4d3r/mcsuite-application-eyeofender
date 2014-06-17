<?php
/**
 * Author: Maxim
 * Date: 12/06/2014
 * Time: 21:09
 * Property of MCSuite
 */

namespace Maxim\Module\TicketBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Route\RouteCollection;

class TicketFormAdmin extends Admin
{
    public $supportsPreviewMode = false;
    protected $security;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Ticket name'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website'))
            ->add('enabled', 'checkbox', array('label' => 'Enabled'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('enabled')
            ->add('website')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('website')
            ->add('enabled')
        ;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
} 