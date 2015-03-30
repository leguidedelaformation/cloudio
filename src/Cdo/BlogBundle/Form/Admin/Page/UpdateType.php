<?php

namespace Cdo\BlogBundle\Form\Admin\Page;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Cdo\BlogBundle\Entity\Page;
use Cdo\BlogBundle\Entity\PageRepository;
use Symfony\Component\Form\FormEvents;use Symfony\Component\Form\FormEvent;

class UpdateType extends PageType
{
    protected $account_id;
    protected $page;
    protected $rank_array;
    protected $em;
    
    public function __construct($account_id, $page, $rank_array, $em)
    {
        $this->account_id = $account_id;
        $this->page = $page;
    	$this->rank_array = $rank_array;
    	$this->em = $em;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $account_id = $this->account_id;
        $page = $this->page;
        $rank_array = $this->rank_array;
        $em = $this->em;
    	$cdo_blog_page_level_max = $GLOBALS['kernel']->getContainer()->getParameter('cdo_blog_page_level_max');
    	
    	$root = $em->getRepository('CdoBlogBundle:Page')->getTree($page->getRealMaterializedPath(), 'p');
    	$parent = $root->getParentNode();
//    	$root = $em->getRepository('CdoBlogBundle:Page')->getTree();
//    	$page_parent_id = ($page->getParentNode())
//    	    ? $page->getParentNode()
//    	    : null;
//        $root->getParentMaterializedPath();
        die ($parent->getNodeId());
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
//            ->add('parent', 'entity', array(
//                'class' => 'CdoBlogBundle:Page',
//                'query_builder' => function(PageRepository $pr) use ($account_id, $page_id, $cdo_blog_page_level_max)
//                {
//                    return $pr->getSuplevelButPageForm($account_id, $page_id, $cdo_blog_page_level_max - 1);
//                },
//                'property' => 'title',
//                'label' => 'Page parente :',
//                'required' => false,
//            ))
            ->add('parent', 'entity', array(
                'class' => 'CdoBlogBundle:Page',
                'query_builder' => function(PageRepository $pr) use ($account_id, $page)
                {
                    return $pr->getRootLevelNodesButPage($account_id, $page->getId());
                },
                'property' => 'title',
                'label' => 'Page parente :',
                'required' => false,
                'mapped' => false,
                'data' => $page_parent_id,
//                'data' => $this->em->getReference("CdoBlogBundle:Page", 19),
            ))
            ->add('rank', 'choice', array(
                'label'    => 'Position :',
                'choices'  => $rank_array,
            ))
        ;
        
        $factory = $builder->getFormFactory();        $builder->addEventListener(            FormEvents::PRE_SET_DATA,            function(FormEvent $event) use ($factory) {	            $page_update = $event->getData();	            if (null === $page_update) {                    return;                }	            if (null === $page_update->getParentNode()) {                    return;                }                if (false === $page_update->getParentNode()->getDisplay()) { 
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
