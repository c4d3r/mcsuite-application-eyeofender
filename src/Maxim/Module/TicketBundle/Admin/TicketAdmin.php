<?php
/**
 * Author: Maxim
 * Date: 13/06/2014
 * Time: 20:58
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

    protected $datagridValues = array(
        '_page' => 1,            // display the first page (default = 1)
        '_sort_order' => 'ASC', // reverse order (default = 'ASC')
        '_sort_by' => 'date'  // name of the ordered field
        // (default = the model's id field, if any)

        // the '_sort_by' key can be of the form 'mySubModel.mySubSubModel.myField'.
    );

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
            ->add('ticket', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add section',      //Specify a custom label
                    'btn_list'      => 'button.list',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No section selected'
                )
            )
            ->add('createdOn', 'datetime')
            ->add('closed')
            ->add('details', 'string', array('template' => 'MaximCMSBundle:Admin:jsonToTable.html.twig'))
            ->add('replies', 'string', array('template' => 'MaximModuleTicketBundle:Admin:showTicketReplies.html.twig'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            //->add('user')
            ->add('closed')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id', null, array('route' => array('name' => 'show')))
            ->add('user')
            ->add('closed', 'boolean')
            ->add('ticket', 'string')
            ->add('details', 'string', array('template' => 'MaximCMSBundle:Admin:jsonToValueString.html.twig'))
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                    'show' => array()
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