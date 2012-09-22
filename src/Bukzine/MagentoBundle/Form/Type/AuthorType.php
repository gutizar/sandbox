<?php

namespace Bukzine\MagentoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class AuthorType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
        	->add('email')
        	->add('firstname')
        	->add('lastname')
        	->add('password', 'repeated', array(
                    'type' => 'password',
                    'invalid_message' => 'Both passwords must match',
                    'options' => array('label' => 'Password')
                ))
        	->add('twitterAccount');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Bukzine\\MagentoBundle\\Entity\\Author',
        );
    }

    public function getName()
    {
        return 'author';
    }
}