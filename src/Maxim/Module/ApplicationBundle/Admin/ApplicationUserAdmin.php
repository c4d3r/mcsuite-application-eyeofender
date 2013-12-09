<?php
/**
 * Author: Maxim
 * Date: 08/12/13
 * Time: 16:43
 * Property of MCSuite
 */

namespace Maxim\Module\ApplicationBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;

class ApplicationUserAdmin extends Admin
{

    public $supportsPreviewMode = false;
    protected $security;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {

        $formMapper
            /*->add('user', 'entity', array(
                'class' => 'Maxim\CMSBundle\Entity\User',
                'label' => 'User',
                'read_only' => true,
                'disabled'  => true,
            ))*/
            ->add('application', 'entity', array('class' => 'MaximModuleApplicationBundle:Application'))
            ->add('denied', 'entity', array(
                'class' => 'Maxim\CMSBundle\Entity\Rank',
                'read_only' => true,
                'disabled'  => true,
            )
        )
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('application')
            ->add('denied')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('user')
            ->add('application')
            ->add('date')
            ->add('denied')
        ;
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            'view',
            array('uri' => $admin->generateUrl('edit', array('id' => $id)))
        );

        $menu->addChild(
            'replies',
            array('uri' => $admin->generateUrl('sonata.admin.module.application.replies.list', array('id' => $id)))
        );
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
} 