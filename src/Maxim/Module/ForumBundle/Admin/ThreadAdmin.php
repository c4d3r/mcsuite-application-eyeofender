<?php
/**
 * Created by IntelliJ IDEA.
 * User: Maxim
 * Date: 29/09/13
 * Time: 16:15
 * To change this template use File | Settings | File Templates.
 */

namespace Maxim\Module\ForumBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;

class ThreadAdmin extends Admin{

    public $supportsPreviewMode = true;
    protected $security;
    protected $doctrine;

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
            ->add('locked', 'checkbox', array('label' => 'Locked', 'required' => false))
            ->add('pinned', 'checkbox', array('label' => 'Pinned', 'required' => false))
            ->add('forum', 'entity', array('class' => 'Maxim\Module\ForumBundle\Entity\Forum'))
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
            ->add('locked')
            ->add('pinned')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->addIdentifier('forum')
            ->add('createdBy')
            ->add('locked', 'boolean', array('editable' => true))
            ->add('pinned', 'boolean', array('editable' => true))
        ;
    }

    public function getForums()
    {
        $return = array();
        $forums = $this->doctrine->getRepository("MaximModuleForumBundle:Forum")->findAll();
        return $forums;
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }


}