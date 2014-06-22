<?php
/**
 * Author: Maxim
 * Date: 22/06/2014
 * Time: 09:51
 * Property of MCSuite
 */

namespace Maxim\Module\ForumBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThreadEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->buildThreadForm($builder, $options);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Maxim\Module\ForumBundle\Entity\ThreadEdit',
            'intention'  => 'thread',
        ));
    }

    public function buildThreadForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('thread', new ThreadType())
            ->add('reason', 'textarea', array(
                'required' => true,
                'attr' => array(
                    'class' => 'redactor-init'
                )
            ));
    }

    public function getName()
    {
        return "maxim_module_forum_thread_edit";
    }
}