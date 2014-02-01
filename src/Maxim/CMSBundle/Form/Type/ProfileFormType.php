<?php
/**
 * Author: Maxim
 * Date: 01/02/14
 * Time: 17:25
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Form\Type;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseProfileFormType;
use Maxim\CMSBundle\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Security\Core\Validator\Constraint\UserPassword as OldUserPassword;
use Symfony\Component\Security\Core\Validator\Constraints\UserPassword;

class ProfileFormType extends BaseProfileFormType
{
    /*
     * {@InheritDoc}
     */
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('phone', 'text', array('label' => 'phone number', 'required' => false))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('skype', 'text', array('label' => 'skype'))
            ->add('dateOfBirth', 'date', array(
                'label' => 'Date of birth',
                'years' => range(1910, 2014),
                'attr' => array(
                    'class' => 'sf_date'
                )
            ))
            ->add('gender', 'choice', array(
                'multiple' => false,
                'choices' => User::getGenderList()
            ))
            ->add('timezone', 'timezone', array('label' => 'timezone'))
            ->add('location', 'country', array('label' => 'country'))
            ->add('biography', 'textarea', array(
                'label' => 'biography',
                'attr'  => array(
                    'class' => 'redactor-init',
                )
            ))
        ;
    }

    public function getName()
    {
        return 'maxim_cms_user_profile';
    }
} 