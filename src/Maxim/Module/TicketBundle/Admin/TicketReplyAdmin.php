<?php
/**
 * Author: Maxim
 * Date: 13/06/2014
 * Time: 20:55
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

class TicketReplyAdmin extends Admin
{
    public $supportsPreviewMode = false;
    protected $parentAssociationMapping = 'userTicket';
    protected $security;
    protected $em;

    public function getNewInstance()
    {
        $admin = $this->isChild() ? $this->getParent() : $this;
        $id = $admin->getRequest()->get("id");
        $ticket = $this->em->getRepository("MaximModuleTicketBundle:UserTicket")->findOneBy(array("id" => $id));
        if(!$ticket)
            die("could not find ticket id: " . $id);

        $instance = parent::getNewInstance();
        $instance->setUserTicket($ticket);
        $instance->setUser($this->security->getToken()->getUser());

        return $instance;
    }

    // Fields to be shown on create/edit forms
    protected function configureFormFields(FormMapper $formMapper)
    {
        if ($this->id($this->getSubject()))
        {
            $formMapper->add('user', 'sonata_type_model_list', array(
                    'btn_add'       => 'Add user',      //Specify a custom label
                    'btn_list'      => 'button.list',     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No user selected'
                )
            )
                ->add('userTicket', 'sonata_type_model_list', array(
                        'btn_add'       => false,      //Specify a custom label
                        'btn_list'      => false,     //which will be translated
                        'btn_delete'    => false,             //or hide the button.
                    ),array(
                        'placeholder' => 'No ticket selected'
                    )
                )
                ->add('createdOn', 'datetime');
        }
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
            ->add('userTicket', 'sonata_type_model_list', array(
                    'btn_add'       => false,      //Specify a custom label
                    'btn_list'      => false,     //which will be translated
                    'btn_delete'    => false,             //or hide the button.
                ),array(
                    'placeholder' => 'No ticket selected'
                )
            )
            ->add('createdOn', 'datetime')
            ->add('text', 'textarea', array(
                'label' => 'Text',
                'attr'  => array(
                    'class' => 'redactor-init',
                    'style' => 'width: 683px;'
                )
            ))
            ->add('replies', 'string', array('template' => 'MaximModuleTicketBundle:Admin:showTicketReplies.html.twig'))
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('createdOn')
        ;
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('userTicket.id', null, array('route' => array('name' => 'show')))
            ->add('user')
            ->add('createdOn')
            ->add('text')
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

    public function setEntityManager($em)
    {
        $this->em = $em;
    }
} 