<?php

namespace Cdo\ClientBundle\Form\Visitor\Individual;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class IndividualType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', 'text', array(
                'label' => 'Nom ou pseudo',
            ))
            ->add('email', 'text', array(
                'label' => 'Email',
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\ClientBundle\Entity\Individual',
            'validation_groups' => array('username', 'email'),
        ));
    }

    public function getName()
    {
        return 'cdo_clientbundle_visitor_individualtype';
    }
}
