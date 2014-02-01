<?php
/**
 * Author: Maxim
 * Date: 01/02/14
 * Time: 18:53
 * Property of MCSuite
 */

namespace Maxim\CMSBundle\Form\Type;

use FOS\UserBundle\Form\Type\RegistrationFormType as BaseRegistrationFormType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends BaseRegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch',
            ))
            ->add('dateOfBirth', 'date', array(
                'years' => range(1910, 2014),
                'attr' => array('class' => 'sf_date')
            ))
            ->add('location', 'country')
            ->add('mcUsername', 'text', array('label' => 'Minecraft.net username or email'))
            ->add('mcPassword', 'password', array('label' => 'Minecraft.net password'))
        ;
    }

    public function getName()
    {
        return 'maxim_cms_user_registration';
    }
} 