<?php
/**
 * Author: Maxim
 * Date: 05/02/14
 * Time: 14:21
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Form\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildPostForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Maxim\Module\ForumBundle\Entity\Post',
            'intention'  => 'thread',
        ));
    }

    public function buildPostForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('text', 'textarea', array(
                'attr' => array(
                    'class' => 'redactor-init'
                )
            ));
    }

    public function getName()
    {
        return 'maxim_module_forum_post';
    }
} 