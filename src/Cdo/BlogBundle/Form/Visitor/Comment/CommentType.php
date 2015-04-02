<?php

namespace Cdo\BlogBundle\Form\Visitor\Comment;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvents;use Symfony\Component\Form\FormEvent;
use Cdo\ClientBundle\Form\Visitor\Individual\IndividualType;

class CommentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array(
                'label' => 'Votre commentaire :',
                'attr' => array(
                    'rows' => '5',
                ),
            ))
            ->add('individual', new IndividualType())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\BlogBundle\Entity\Comment',
            'validation_groups' => array('content'),
        ));
    }

    public function getName()
    {
        return 'cdo_blogbundle_visitor_comment_commenttype';
    }
}
