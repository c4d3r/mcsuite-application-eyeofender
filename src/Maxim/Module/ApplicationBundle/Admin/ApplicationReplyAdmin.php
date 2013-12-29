<?php
/**
 * Author: Maxim
 * Date: 08/12/13
 * Time: 17:47
 * Property of MCSuite
 */

namespace Maxim\Module\ApplicationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Route\RouteCollection;

class ApplicationReplyAdmin extends Admin
{
    public $supportsPreviewMode = false;
    protected $parentAssociationMapping = 'application';
    protected $security;
    protected $em;

    public function getNewInstance()
    {
        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');
        $instance = parent::getNewInstance();
        $application = $this->em->getRepository("MaximModuleApplicationBundle:UserApplication")->findOneBy(array("id" => $id));
        /*if(!$application)
            throw new NotFoundException("Could not find application"); */
        $instance->setApplication($application);
        $instance->setUser($this->security->getToken()->getUser());
        $instance->setDate(new \DateTime("now"));
        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('text', 'textarea', array(
                'label' => 'Text',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('date')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('date')
            ->add('user')
            ->add('text', null, array('safe' => true))
        ;
    }

    /**
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function setEntityManager($em)
    {
        $this->em = $em;
    }
    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
} 