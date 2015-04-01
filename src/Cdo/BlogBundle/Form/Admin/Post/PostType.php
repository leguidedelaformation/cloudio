<?php

namespace Cdo\BlogBundle\Form\Admin\Post;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Cdo\BlogBundle\Entity\CategoryRepository;

class PostType extends AbstractType
{
    protected $account_id;
    
    public function __construct($account_id)
    {
        $this->account_id = $account_id;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $account_id = $this->account_id;
        
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
            ->add('categorys', 'entity', array(
                'class'         => 'Cdo\BlogBundle\Entity\Category',
                'query_builder' => function(CategoryRepository $cr) use ($account_id)
                {
                    return $cr->getAllForm($account_id);
                },
                'property' => 'title',
                'multiple' => true,
                'label' => 'Catégorie(s) :',
                'expanded' => true,
            ))
            ->add('display', 'choice', array(
                'label' => 'Publier :',
                'choices' => array(
                    '1' => 'Oui',
                    '0' => 'Non',
                ),
                'expanded' => true,
            ))
            ->add('releasedate', 'date', array(
                'widget' => 'single_text',
                'input'  => 'datetime',
                'format' => 'dd/MM/yyyy H:m:s',
                'attr'   => array('class' => 'date'),
                'label'  => 'Date :',
            ))
            ->add('author', 'choice', array(
                'label' => 'Afficher l\'auteur :',
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
            'data_class' => 'Cdo\BlogBundle\Entity\Post',
            'validation_groups' => array('title'),
        ));
    }

    public function getName()
    {
        return 'cdo_blogbundle_admin_post_posttype';
    }
}
