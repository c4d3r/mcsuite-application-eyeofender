<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 16:15
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
class UserAdmin extends Admin{

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
        ;
    }
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('country', 'sonata_type_model_list', array(
                    'btn_add'       => false,      //Specify a custom label
                    'btn_list'      => 'List countries',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No country selected'
                )
            )
            ->add('username', 'text', array('label' => 'Username'))
            ->add('email', 'text', array('label' => 'E-mail'))
            ->add('lastIp', 'text', array('label' => 'last Ip-address'))
            ->add('skype', 'text', array('label' => 'Skype'))
            ->add('dateOfBirth', 'date', array('label' => 'Date of birth'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
            ->add('username')
            ->add('country')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('username')
            ->add('email')
            ->add('lastIp')
            ->add('country')
        ;
    }
}