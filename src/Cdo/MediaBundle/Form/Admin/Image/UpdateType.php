<?php

namespace Cdo\MediaBundle\Form\Admin\Image;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('alt', 'text', array(
                'label' => 'Titre :',
            ))
            ->add('navbarlogo', 'checkbox', array(
                'label' => 'Définir comme logo :',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\MediaBundle\Entity\Image',
            'validation_groups' => array('alt'),
        ));
    }

    public function getName()
    {
        return 'cdo_mediabundle_account_image_updatetype';
    }
}
