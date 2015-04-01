<?php

namespace Cdo\BlogBundle\Form\Admin\Page;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Titre :',
            ))
            ->add('content', 'textarea', array(
                'label' => 'Contenu :',
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'contentmanagement',
                    'rows' => '20',
                ),
                'required' => false,
            ))
            ->add('display', 'choice', array(
                'label' => 'Publier :',
                'choices' => array(
                    '1' => 'Oui',
                    '0' => 'Non',
                ),
                'expanded' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\\Entity\Page',
            'validation_groups' => array('title'),
        ));
    }

    public function getName()
    {
        return 'cdo_blogbundle_admin_page_pagetype';
    }
}
