<?php
/**
 * Author: Maxim
 * Date: 05/11/13
 * Time: 11:09
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;
use Sonata\AdminBundle\Admin\Admin;
use Maxim\CMSBundle\Entity\Announcement;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

class AnnouncementAdmin extends Admin
{

    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'id'  // name of the ordered field
    );


    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('type', 'choice', array(
                'multiple' => false,
                'choices' => Announcement::getTypeList(),
                'attr' => array('class' => 'form-control')
            ))
            ->add('startdate', 'datetime', array(
                'label' => 'Start date'
            ))
            ->add('enddate', 'datetime', array(
                'label' => 'End date'
            ))
            ->add('website', 'entity', array(
                'class' => 'Maxim\CMSBundle\Entity\Website',
                'label' => "Website"))
            ->add('text', 'textarea', array(
                'label' => 'Text',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('website')
            ->add('text')
            ->add('type')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('name')
            ->add('description')
        ;
    }

}