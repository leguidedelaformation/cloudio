<?php

namespace Cdo\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ProfileFormType extends BaseType
{
    protected function buildUserForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->remove('username')
            ->remove('email')
            ->add('username', 'text', array(
                'label' => 'Pseudo :',
                'disabled' => true,
            ))
            ->add('email', 'email', array(
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
                'disabled' => true,
            ))
            ->add('firstname', 'text', array(
                'label' => 'Prénom :',
            ))
            ->add('lastname', 'text', array(
                'label' => 'Nom :',
            ))
            ->add('phone', 'text', array(
                'label' => 'Téléphone :',
            ))
        ;
    }

    public function getParent()
    {
        return 'fos_user_profile';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\UserBundle\Entity\User',
            'intention'  => 'profile',
            'validation_groups' => array('firstname', 'lastname', 'phone'),
        ));
    }
    
    public function getName()
    {
        return 'cdo_user_profile';
    }
}
