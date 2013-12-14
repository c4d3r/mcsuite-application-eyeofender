<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 14:07
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class PurchaseAdmin extends Admin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
        ;
    }
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            /*->add('user', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add user',      //Specify a custom label
                    'btn_list'      => 'button.list',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No user selected'
                )
            )*/
            ->add('name', 'text', array('label' => 'Ingame username'))
            ->add('storeItem', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add item',      //Specify a custom label
                    'btn_list'      => 'List',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No item selected'
                )
            )
            ->add('status', 'text', array('label' => 'Purchase status'))
            ->add('transaction', 'text', array('label' => 'transaction id'))
            ->add('amount', 'integer', array('label' => 'amount paid'))
            ->add('ip', 'text', array('label' => 'IP-Address'))
            ->add('date')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('amount')
            ->add('status')
            ->add('transaction')
            ->add('name')
            ->add('date')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('transaction')
            //->add('user')
            ->add('name')
            //->add('storeItem')
            ->add('status')
            ->add('amount')
        ;
    }
} 