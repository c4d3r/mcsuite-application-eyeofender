<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 29/09/13
 * Time: 16:55
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Maxim\CMSBundle\Entity\StoreItem;

class StoreItemAdmin extends Admin{

    protected $configs;

    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'DESC', // reverse order (default = 'ASC')
        '_sort_by' => 'website'  // name of the ordered field
        // (default = the model's id field, if any)

        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );


    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', 'text', array('label' => 'Item name'))
            ->add('description', 'textarea', array(
                'label' => 'Item description',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ))
            ->add('amount', 'money', array(
                'label' => 'Item price',
                'currency' => $this->configs['currency']
            ))
            ->add('tax', 'percent', array('label' => 'Item tax'))
            ->add('visible', 'checkbox', array('label' => 'Item visibility'))
            ->add('type', 'choice', array(
                'multiple' => false,
                'choices' => StoreItem::getTypeList()
            ))
            ->add('command', 'textarea', array(
                'label' => 'Item Command',
                'attr'  => array(
                    'class' => 'ace-init',
                    'style' => 'width: 683px;height: 250px;',
                    'data-editor' => "sql"
                )
            ))
            ->add('image', 'text', array('label' => 'Item image'))
            ->add('reduction', 'money', array(
                'label' => 'Item reduction',
                'currency' => $this->configs['currency']
            ))
            ->add('priority', 'integer', array('label' => 'Item priority'))
            ->add('storeCategory', 'entity', array('class' => 'Maxim\CMSBundle\Entity\StoreCategory'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name')
            ->add('visible')
            ->add('tax')
            ->add('storeCategory')
            ->add('website')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name')
            ->add('visible', 'boolean', array('editable' => true))
            ->add('amount')
            ->add('tax')
            ->add('storeCategory')
            ->add('website')
        ;
    }

    public function setConfigs($configs)
    {
        $this->configs = $configs;
    }

}