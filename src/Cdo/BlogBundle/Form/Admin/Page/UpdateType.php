<?php

namespace Cdo\BlogBundle\Form\Admin\Page;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Cdo\BlogBundle\Entity\PageRepository;
use Symfony\Component\Form\FormEvents;use Symfony\Component\Form\FormEvent;

class UpdateType extends PageType
{
    protected $account_id;
    protected $page_id;
    protected $rank_array;
    protected $page_level_max;
    
    public function __construct($account_id, $page_id, $rank_array, $page_level_max)
    {
        $this->account_id = $account_id;
        $this->page_id = $page_id;
    	$this->rank_array = $rank_array;
        $this->page_level_max = $page_level_max;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $account_id = $this->account_id;
        $page_id = $this->page_id;
        $rank_array = $this->rank_array;
        $page_level_max = $this->page_level_max;
        
        parent::buildForm($builder, $options);
        
        $builder
            ->add('display', 'choice', array(
                'label' => 'Publier :',
                'choices' => array(
                    '1' => 'Oui',
                    '0' => 'Non',
                ),
                'expanded' => true,
            ))
            ->add('parent', 'entity', array(
                'class' => 'CdoBlogBundle:Page',
                'query_builder' => function(PageRepository $pr) use ($account_id, $page_id, $page_level_max)
                {
                    return $pr->getSuplevelButPageForm($account_id, $page_id, $page_level_max - 1);
                },
                'property' => 'title',
                'label' => 'Page parente :',
                'required' => false,
            ))
            ->add('rank', 'choice', array(
                'label'    => 'Position :',
                'choices'  => $rank_array,
            ))
        ;
        
        $factory = $builder->getFormFactory();        $builder->addEventListener(            FormEvents::PRE_SET_DATA,            function(FormEvent $event) use ($factory) {	            $page = $event->getData();	            if (null === $page) {                    return;                }	            if (null === $page->getParent()) {                    return;                }                if (false === $page->getParent()->getDisplay()) { 
	                $event->getForm()->remove('display');
	            }            }
        );
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
        return 'cdo_blogbundle_admin_page_updatetype';
    }
}
