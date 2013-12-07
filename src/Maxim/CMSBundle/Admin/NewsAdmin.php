<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 29/09/13
 * Time: 15:03
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\CMSBundle\Admin;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;

class NewsAdmin extends Admin{

    public $supportsPreviewMode = true;
    protected $security;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        $instance->setCreatedOn(new \DateTime("now"));
        $instance->setCreatedBy($this->security->getToken()->getUser());
        $instance->setPinned(true);
        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('forum', 'entity', array('class' => 'Maxim\Module\ForumBundle\Entity\Forum'));

        $formMapper
            ->add('locked', 'checkbox', array('label' => 'Locked', 'required' => false))
            ->add('title', 'text', array('label' => 'title'))
            ->add('text', 'textarea', array(
                'label' => 'Text',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ));
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('forum')
            ->add('title')
            ->add('text')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('forum')
            ->add('locked', 'boolean', array('editable' => true))
            ->add('pinned', 'boolean', array('editable' => true))
            ->add('title')
            ->add('createdBy')
        ;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
}