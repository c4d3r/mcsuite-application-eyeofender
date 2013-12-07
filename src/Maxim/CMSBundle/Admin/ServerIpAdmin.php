<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 02/10/13
 * Time: 20:22
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class ServerIpAdmin extends Admin {

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('ip', 'text', array('label' => 'Server ip'))
            ->add('host', 'text', array('label' => 'Server logon ip'))
            ->add('server', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Server'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('server')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('ip')
            ->add('host')
            ->add('server')
        ;
    }
}