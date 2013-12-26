<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 11:39
 * Property of MCSuite
 */

namespace Maxim\Module\TicketBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Security\Core\SecurityContext;
use Sonata\AdminBundle\Route\RouteCollection;
use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Show\ShowMapper;

class TicketAdmin extends Admin
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
            ->add('description', 'textarea', array(
                'label' => 'Description',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ))
            ->add('closed', 'checkbox', array('label' => 'Ticket closed'))
            ->add('status', 'text')
            ->add('user', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add user',      //Specify a custom label
                    'btn_list'      => 'button.list',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No user selected'
                )
            )
            ->add('description', 'textarea', array(
                'label' => 'Description',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ))
            ->add('date', 'datetime')
            ->add('closed', 'checkbox')
            ->add('status', 'text')
            ->add('replies', 'sonata_type_collection', array(
                // Prevents the "Delete" option from being displayed
                'type_options' => array('delete' => false),
                'btn_add'       => 'Add reply',      //Specify a custom label
            ), array(
                'edit' => 'inline',
                'inline' => 'table',
                'sortable' => 'position',
            ))
            /* ->add('section', 'sonata_type_model_list', array(
                     'btn_add'       => 'Add section',      //Specify a custom label
                     'btn_list'      => 'button.list',     //which will be translated
                     'btn_delete'    => false,             //or hide the button.
                 ),array(
                     'placeholder' => 'No section selected'
                 )
             )
       /*
             ->add('website', 'sonata_type_model_list', array(
                     'btn_add'       => 'Add section',      //Specify a custom label
                     'btn_list'      => 'button.list',     //which will be translated
                     'btn_delete'    => false,             //or hide the button.
                 ),array(
                     'placeholder' => 'No website selected'
                 )
             )     */
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('delete')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('description')
            ->add('user', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add user',      //Specify a custom label
                    'btn_list'      => 'button.list',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No user selected'
                )
            )
            ->add('section', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add section',      //Specify a custom label
                    'btn_list'      => 'button.list',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No section selected'
                )
            )
            ->add('date')
            ->add('closed')
            ->add('statusChangedOn', 'datetime')
            ->add('status')
            ->add('statusChangedBy')
            ->add('replies', 'string', array('template' => 'MaximModuleTicketBundle:Admin:showTicketReplies.html.twig'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('closed')
            ->add('website')
            /*->add('status')
            ->add('section')*/
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('route' => array('name' => 'show')))
            /*->add('user.username')
             ->add('section')*/
            ->add('closed')
            ->add('status')
            ->add('description')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                    'view' => array()
                ),
                "label" => 'actions'
            ))
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
            array('uri' => $admin->generateUrl('sonata.admin.module.ticket.replies.list', array('id' => $id)))
        );
    }

    public function setSecurityContext(SecurityContext $security)
    {
        $this->security = $security;
    }
} 