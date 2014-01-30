<?php
/**
 * Author: Maxim
 * Date: 16/12/13
 * Time: 12:04
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;


class GroupAdmin extends Admin
{
    public $supportsPreviewMode = true;
    protected $security;

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Group name', 'required' => true))
            ->add('roles', 'choice', array(
                'multiple' => false,
                'choices' => Group::getRoleList()
            ))
            ->add('title', 'text', array('label' => 'title'))
            ->add('text', 'textarea', array(
                'label' => 'Text',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('forum')
            ->add('title')
            ->add('text')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('forum')
            ->add('locked', 'boolean', array('editable' => true))
            ->add('pinned', 'boolean', array('editable' => true))
            ->add('title')
            ->add('createdBy')
        ;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
} 