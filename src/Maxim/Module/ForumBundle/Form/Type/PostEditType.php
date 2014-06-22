<?php
/**
 * Author: Maxim
 * Date: 22/06/2014
 * Time: 10:08
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PostEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildThreadForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Maxim\Module\ForumBundle\Entity\PostEdit',
            'intention'  => 'thread',
        ));
    }

    public function buildThreadForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('post', new PostType())
            ->add('reason', 'textarea', array(
                'required' => true,
                'attr' => array(
                    'class' => 'redactor-init'
                )
            ));
    }

    public function getName()
    {
        return "maxim_module_forum_post_edit";
    }
}