<?php

namespace Cdo\BlogBundle\Form\Admin\Comment;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentType extends AbstractType
{
    protected $comment_status;
    
    public function __construct($comment_status)
    {
        $this->comment_status = $comment_status;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $comment_status = $this->comment_status;
        
        $builder
            ->add('content', 'textarea', array(
                'label' => 'Commentaire',
                'attr' => array(
                    'rows' => '5',
                ),
            ))
            ->add('status', 'choice', array(
                'label' => 'Statut',
                'choices' => $comment_status,
                'expanded' => true,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\BlogBundle\Entity\Comment',
            'validation_groups' => array('comment'),
        ));
    }

    public function getName()
    {
        return 'cdo_blogbundle_admin_comment_commenttype';
    }
}
