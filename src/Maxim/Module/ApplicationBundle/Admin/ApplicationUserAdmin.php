<?php
/**
 * Author: Maxim
 * Date: 08/12/13
 * Time: 16:43
 * Property of MCSuite
 */

namespace Maxim\Module\ApplicationBundle\Admin;

use Maxim\Module\ApplicationBundle\Form\DataTransformer\JsonArrayToTableTransformer;
use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Show\ShowMapper;

class ApplicationUserAdmin extends Admin
{

    public $supportsPreviewMode = false;
    protected $security;

    public function getNewInstance()
    {
        $instance = parent::getNewInstance();
        return $instance;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
            ->remove('edit')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('user', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add user',      //Specify a custom label
                    'btn_list'      => 'button.list',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No user selected'
                )
            )
            ->add('application')
            ->add('denied')
            ->add('details', 'string', array('template' => 'MaximCMSBundle:Admin:jsonToTable.html.twig'))
            ->add('replies', 'string', array('template' => 'MaximModuleApplicationBundle:Admin:showUserApplicationReplies.html.twig'))
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
            ->addIdentifier('id', null, array('route' => array('name' => 'show')))
            ->add('user')
            ->add('application')
            ->add('date')
            ->add('denied', 'boolean', array('editable' => true))
        ;
    }

    protected function configureSideMenu(MenuItemInterface $menu, $action, AdminInterface $childAdmin = null)
    {
        if (!$childAdmin && !in_array($action, array('edit', 'show'))) {
            return;
        }

        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get('id');

        $menu->addChild(
            'view',
            array('uri' => $admin->generateUrl('show', array('id' => $id)))
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