<?php

namespace Bukzine\MagentoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class BookType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
        	->add('name')
            ->add('author_name')
        	->add('description')
        	->add('short_description')
        	->add('num_pages')
        	->add('price');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Bukzine\\MagentoBundle\\Entity\\Book',
        );
    }

    public function getName()
    {
        return 'book';
    }
}