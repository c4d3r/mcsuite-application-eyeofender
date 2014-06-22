<?php
/**
 * Author: Maxim
 * Date: 05/02/14
 * Time: 13:20
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThreadType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildThreadForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Maxim\Module\ForumBundle\Entity\Thread',
            'intention'  => 'thread',
        ));
    }

    public function buildThreadForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text')
            ->add('text', 'textarea', array(
                'attr' => array(
                    'class' => 'redactor-init'
                )
            ));
    }

    public function getName()
    {
        return 'maxim_module_forum_thread';
    }
} 