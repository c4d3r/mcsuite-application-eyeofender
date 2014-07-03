<?php
/**
 * Author: Maxim
 * Date: 13/06/2014
 * Time: 13:14
 * Property of MCSuite
 */

namespace Maxim\Module\ApplicationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ReplyType extends AbstractType
{
    public function buildForm( FormBuilderInterface $builder, array $options )
    {
        $builder->add( 'text', 'textarea' ,  array(
            'label' => 'Text',
            'attr'  => array(
                'class' => 'redactor-init',
            )
        ));
    }

    function getName() {
        return 'ReplyType';
    }
} 