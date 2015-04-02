<?php

namespace Cdo\BlogBundle\Form\Admin\Page;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Cdo\BlogBundle\Entity\PageRepository;

class CreateType extends PageType
{
    protected $account_id;
    protected $page_level_max;
    
    public function __construct($account_id, $page_level_max)
    {
        $this->account_id = $account_id;
        $this->page_level_max = $page_level_max;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $account_id = $this->account_id;
        $page_level_max = $this->page_level_max;
        
        parent::buildForm($builder, $options);
        
        $builder
            ->add('parent', 'entity', array(
                'class' => 'CdoBlogBundle:Page',
                'query_builder' => function(PageRepository $pr) use ($account_id, $page_level_max)
                {
                    return $pr->getSuplevelForm($account_id, $page_level_max - 1);
                },
                'property' => 'title',
                'label' => 'Page parente',
                'required' => false,
            ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Cdo\BlogBundle\Entity\Page',
            'validation_groups' => array('title'),
        ));
    }

    public function getName()
    {
        return 'cdo_blogbundle_admin_page_createtype';
    }
}
