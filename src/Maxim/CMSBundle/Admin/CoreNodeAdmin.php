<?php
/**
 * Author: Maxim
 * Date: 04/11/13
 * Time: 19:45
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Maxim\CMSBundle\Entity\CodeNode;

class CoreNodeAdmin extends Admin
{
    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'id'  // name of the ordered field
    );

    public function preUpdate($object)
    {
        $object->setUpdatedOn(new \DateTime("now"));
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('node', 'text', array('label' => 'Node name (ex. shop.terms)'))
            ->add('content', 'textarea', array(
                'label' => 'Node content',
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
            ->add('node')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('node')
            ->add('description')
        ;
    }
}