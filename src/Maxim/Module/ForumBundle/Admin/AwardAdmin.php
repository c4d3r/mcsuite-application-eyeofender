<?php
/**
 * Author: Maxim
 * Date: 01/07/2014
 * Time: 12:14
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Admin;


use Maxim\CMSBundle\Entity\Award;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;

class AwardAdmin extends Admin
{
    public $supportsPreviewMode = true;
    protected $security;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        $instance->setCreatedOn(new \DateTime("now"));
        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Award name'))
            ->add('description', 'text', array('label' => 'Description'))
            ->add('type', 'choice', array(
                'multiple' => false,
                'choices' => Award::getTypeList(),
                'attr' => array('class' => 'form-control')
            ))
            ->add('image', 'sonata_type_model_list', array('label' => 'Award icon'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website', 'attr' => array('class' => 'form-control')))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('website')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('description')
            ->add('createdOn')
            ->add('website')
        ;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
} 