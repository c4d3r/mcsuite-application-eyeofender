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
use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Route\RouteCollection;

class ApplicationReplyAdmin extends Admin
{
    public $supportsPreviewMode = false;
    protected $parentAssociationMapping = 'application';
    protected $security;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        $instance->setApplication($application);
        $instance->setUser($this->securityContext->getToken()->getUser());
        $instance->setDate(new \DateTime("now"));
        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        if (!$this->isChild()) {
            $formMapper->add('application', 'sonata_type_model_list');
//            $formMapper->add('post', 'sonata_type_admin', array(), array('edit' => 'inline'));
        }

        $formMapper
            ->add('user', 'entity', array('class' => 'Maxim\CMSBundle\Entity\User'))
            ->add('text', 'text')
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
            ->add('text')
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