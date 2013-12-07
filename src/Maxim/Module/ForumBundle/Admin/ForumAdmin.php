<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 29/09/13
 * Time: 15:53
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;

class ForumAdmin extends Admin{

    public $supportsPreviewMode = true;
    protected $security;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        $instance->setCreatedOn(new \DateTime("now"));
        $instance->setCreatedBy($this->security->getToken()->getUser());
        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text', array('label' => 'Forum Title'))
            ->add('description', 'text', array('label' => 'Forum description'))
            ->add('sort', 'integer', array('label' => 'Forum sort'))
            ->add('showOnHome', 'checkbox', array('label' => 'Show on home page', 'required' => false))
            ->add('category', 'entity',
                array('class' => 'Maxim\Module\ForumBundle\Entity\Category'),
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
            ->add('title')
            ->add('category')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('category')
            ->add('createdOn')
            ->add('createdBy')
        ;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
}