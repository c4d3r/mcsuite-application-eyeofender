<?php
/**
 * Author: Maxim
 * Date: 02/02/14
 * Time: 12:51
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PageAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Page name'))
            ->add('url', 'text', array('label' => 'Page url (ex. staff-page)'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website', 'label' => 'website'))
            ->add('content', 'textarea', array(
                'label' => 'Page content',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 100%;min-height:500px;'
                )
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('url')
            ->add('website')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->addIdentifier('url')
            ->add('website')
        ;
    }
} 