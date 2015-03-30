<?php

namespace Cdo\MediaBundle\Form\Admin\Image;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UploadType extends UpdateType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        
        $builder
            ->add('file', 'file', array(
                'label' => 'Fichier :',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\MediaBundle\Entity\Image',
            'validation_groups' => array('file', 'alt'),
        ));
    }

    public function getName()
    {
        return 'cdo_mediabundle_account_image_uploadtype';
    }
}
