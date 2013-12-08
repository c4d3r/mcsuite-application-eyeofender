<?php
/**
 * Author: Maxim
 * Date: 08/12/13
 * Time: 11:02
 * Property of MCSuite
 */

namespace Maxim\Module\ApplicationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Route\RouteCollection;
class ApplicationFormAdmin extends Admin
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
            ->add('name', 'text', array('label' => 'Application name'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website'))
            ->add('rank', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Rank'))
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
            ->add('rank')
            ->add('website')
            ->add('enabled')
        ;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
} 