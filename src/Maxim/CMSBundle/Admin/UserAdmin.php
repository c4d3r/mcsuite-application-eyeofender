<?php
/**
 * Author: Maxim
 * Date: 14/12/13
 * Time: 16:15
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Maxim\CMSBundle\Entity\User;
use Maxim\CMSBundle\Twig\Extension\FriendExtension;
class UserAdmin extends Admin
{

    public function getFormBuilder()
    {
        $this->formOptions['data_class'] = $this->getClass();

        $options = $this->formOptions;
        $options['validation_groups'] = (!$this->getSubject() || is_null($this->getSubject()->getId())) ? 'Registration' : 'Profile';

        $formBuilder = $this->getFormContractor()->getFormBuilder( $this->getUniqid(), $options);

        $this->defineFormBuilder($formBuilder);

        return $formBuilder;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
        ;
    }
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('username', 'text', array('label' => 'Username'))
                ->add('email', 'email', array('label' => 'E-mail'))
                ->add('lastIp', 'text', array('label' => 'last Ip-address', 'required' => false))
            ->end()
            ->with('Profile')
                ->add('location', 'country', array('required' => false))
                ->add('skype', 'text', array('label' => 'Skype', 'required' => false))
                ->add('dateOfBirth', 'date', array('years' => range(1910, 2014), 'label' => 'Date of birth', 'required' => false))
                ->add('gender', 'choice', array(
                    'required' => false,
                    'multiple' => false,
                    'choices' => User::getGenderList()
                ))
            ->end()
            ->with('Groups')
                ->add('groups', null, array(
                    'required' => false,
                    'multiple' => true
                ))
            ->end()
            ->with('Forums')
                ->add('awards', null, array('required' => false, 'multiple' => true))
            ->end()
        ;
    }

    // Fields to be shown on filter forms
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('email')
            ->add('username')
            ->add('location')
            ->add('groups')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getExportFields()
    {
        // avoid security field to be exported
        return array_filter(parent::getExportFields(), function($v) {
            return !in_array($v, array('password', 'salt'));
        });
    }

    // Fields to be shown on lists
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('id')
            ->add('username')
            ->add('email')
            ->add('lastIp')
            ->add('location')
            ->add('groups')
            ->add('_action', 'actions', array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                ),
                "label" => 'actions'
            ))
        ;
        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', array('template' => 'SonataAdminBundle:Admin:Field/impersonating.html.twig'))
            ;
        }
    }
}