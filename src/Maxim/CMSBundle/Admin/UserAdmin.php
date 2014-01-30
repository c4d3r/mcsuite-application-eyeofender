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
use Maxim\CMSBundle\Entity\User;
use Maxim\CMSBundle\Twig\Extension\FriendExtension;
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
            ->with('General')
                ->add('location', 'country', array('required' => false))
                ->add('username', 'text', array('label' => 'Username'))
                ->add('email', 'email', array('label' => 'E-mail'))
                ->add('lastIp', 'text', array('label' => 'last Ip-address'))
                ->add('skype', 'text', array('label' => 'Skype', 'required' => false))
                ->add('dateOfBirth', 'date', array('label' => 'Date of birth'))
            ->end()
            ->with('Groups')
                ->add('groups')
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
            ->add('username')
            ->add('location')
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
            ->add('location')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
                "label" => 'actions'
            ))
        ;
    }
}