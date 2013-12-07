<?php

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;


class ServerAdmin extends Admin{

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Server name'))
            ->add('description', 'text', array('label' => 'Server description'))
            ->add('image', 'text', array('label' => 'Server image'))
            ->add('abbr', 'text', array('label' => 'Server abbreviation'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('abbr')
            ->add('website')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('image')
            ->add('abbr')
            ->add('website')
        ;
    }
}