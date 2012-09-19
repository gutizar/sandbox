<?php

namespace Bukzine\MagentoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Bukzine\MagentoBundle\Form\Type\TagType;

class TaskType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
        	->add('description')
        	->add('tags', 'collection', array(
        		'type' => new TagType(),
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
        		));
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Bukzine\MagentoBundle\Entity\Task',
        );
    }

    public function getName()
    {
        return 'task';
    }
}