<?php

namespace Bukzine\MagentoBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class UploadFileType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
    }

    public function getName()
    {
        return 'uploadFile';
    }
}