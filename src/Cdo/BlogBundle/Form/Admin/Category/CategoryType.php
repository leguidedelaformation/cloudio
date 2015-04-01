<?php

namespace Cdo\BlogBundle\Form\Admin\Category;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', 'text', array(
                'label' => 'Titre :',
            ))
            ->add('content', 'textarea', array(
                'label' => 'Description :',
                'attr' => array(
                    'rows' => '5',
                ),
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\BlogBundle\Entity\Category',
            'validation_groups' => array('title'),
        ));
    }

    public function getName()
    {
        return 'cdo_blogbundle_admin_category_categorytype';
    }
}
