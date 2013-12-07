<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 29/09/13
 * Time: 16:13
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Admin;



use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends Admin {

    public $supportsPreviewMode = true;

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('text', 'text', array('label' => 'Post Title'))
            ->add('thread', 'entity',
                array('class' => 'Maxim\Module\ForumBundle\Entity\Thread'),
                array(
                    'edit' => 'inline',
                    'sortable' => 'pos',
                    'inline' => 'table',
                ));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('thread')
            ->add('text')
            ->add('createdBy', 'doctrine_orm_string')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('thread')
            ->add('text')
            ->add('createdBy')
        ;
    }
}