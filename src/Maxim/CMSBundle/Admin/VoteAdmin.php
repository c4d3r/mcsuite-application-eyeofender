<?php
/**
 * Author: Maxim
 * Date: 13/11/13
 * Time: 21:38
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;


class VoteAdmin extends Admin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Vote site name'))
            ->add('link', 'text', array('label' => 'Vote site link'))
            ->add('reset', 'text', array('label' => 'Vote site reset'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('website')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('link')
            ->add('website')
        ;
    }
}