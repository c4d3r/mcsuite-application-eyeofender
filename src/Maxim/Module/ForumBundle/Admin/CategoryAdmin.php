<?php
namespace Maxim\Module\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;

class CategoryAdmin extends Admin {

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
            ->add('title', 'text', array('label' => 'Category Title'))
            ->add('sort', 'integer', array('label' => 'Forum sort'))
            ->add('description', 'text', array('label' => 'Category description'))
            ->add('website', 'entity', array('class' => 'Maxim\CMSBundle\Entity\Website', 'attr' => array('class' => 'form-control')))
            ->add('visible', 'checkbox')
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('website')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('description')
            ->add('createdOn')
            ->add('website')
        ;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }

}