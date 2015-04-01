<?php

namespace Cdo\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cdo\BlogBundle\Entity\Category;
use Cdo\BlogBundle\Form\Admin\Category\CategoryType;
use Cdo\BlogBundle\Event\CategoryEvents;
use Cdo\BlogBundle\Event\CategoryPostEvent;

/**
 * @Route("/admin/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_admin_category_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        $category_collection = $em->getRepository('CdoBlogBundle:Category')
                                  ->getAll($account);
        
        return array(
            'category_collection' => $category_collection,
        );
    }

    /**
     * @Route("/create", name="cdo_blog_admin_category_create")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function createAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');
        
        $account = $this->getUser()->getAccount();
        
        $category = new Category;
        $category->setAccount($account);
        
        $form = $this->createForm(new CategoryType($account->getId()), $category);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                $em->persist($category);
                $em->flush();
                
                $event = new CategoryPostEvent($securityContext, $category, $em);
                $this->get('event_dispatcher')->dispatch(CategoryEvents::onCategoryPost, $event);
                $event->checkSlug();
                
                $this->get('session')->getFlashBag()->add('success', 'La catégorie « '.$category->getTitle().' » a été créée.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_category_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{category}", name="cdo_blog_admin_category_update")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function updateAction(Category $category, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');
        
        $account = $this->getUser()->getAccount();
        if ($account->getId() != $category->getAccount()->getId()) {
            return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $form = $this->createForm(new CategoryType($account->getId()), $category);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                $em->flush();
                
                $event = new CategoryPostEvent($securityContext, $category, $em);
                $this->get('event_dispatcher')->dispatch(CategoryEvents::onCategoryPost, $event);
                $event->checkSlug();
                
                $this->get('session')->getFlashBag()->add('success', 'La catégorie « '.$category->getTitle().' » a été mise à jour.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_category_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'category' => $category,
        );
    }

    /**
     * @Route("/remove/{category}", name="cdo_blog_admin_category_remove")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function removeAction(Category $category, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        
        $account = $this->getUser()->getAccount();
        if ($account->getId() != $category->getAccount()->getId()) {
	        return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $category_title = $category->getTitle();
        
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->getRequest();
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            if ($form->isValid()) {
                $em->remove($category);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'La catégorie « '.$category_title.' » a été supprimée.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_category_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'category' => $category,
        );
    }
}
