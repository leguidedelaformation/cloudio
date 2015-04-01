<?php

namespace Cdo\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cdo\BlogBundle\Entity\Post;
use Cdo\BlogBundle\Form\Admin\Post\PostType;
use Cdo\BlogBundle\Event\PostEvents;
use Cdo\BlogBundle\Event\PostPostEvent;

/**
 * @Route("/admin/post")
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_admin_post_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        $post_collection = $em->getRepository('CdoBlogBundle:Post')
                              ->getAll($account);
        
        return array(
            'post_collection' => $post_collection,
        );
    }

    /**
     * @Route("/create", name="cdo_blog_admin_post_create")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function createAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');
        
        $user = $this->getUser();
        $account = $user->getAccount();
        
        $post = new Post;
        $post->setAccount($account);
        $post->setUser($user);
        
        $form = $this->createForm(new PostType($account->getId()), $post);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                $em->persist($post);
                $em->flush();
                
                $event = new PostPostEvent($securityContext, $post, $em);
                $this->get('event_dispatcher')->dispatch(PostEvents::onPostPost, $event);
                $event->categoryDisplay();
                $event->checkSlug();
                
                $this->get('session')->getFlashBag()->add('success', 'L\'article « '.$post->getTitle().' » a été créé.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_post_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'user' => $user,
        );
    }

    /**
     * @Route("/update/{post}", name="cdo_blog_admin_post_update")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function updateAction(Post $post, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');
        
        $user = $this->getUser();
        $account = $user->getAccount();
        if ($account->getId() != $post->getAccount()->getId()) {
            return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $form = $this->createForm(new PostType($account->getId()), $post);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                $em->flush();
                
                $event = new PostPostEvent($securityContext, $post, $em);
                $this->get('event_dispatcher')->dispatch(PostEvents::onPostPost, $event);
                $event->categoryDisplay();
                $event->checkSlug();
                
                $this->get('session')->getFlashBag()->add('success', 'L\'article « '.$post->getTitle().' » a été mis à jour.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_post_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'post' => $post,
            'user' => $user,
        );
    }

    /**
     * @Route("/remove/{post}", name="cdo_blog_admin_post_remove")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function removeAction(Post $post, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');
        
        $account = $this->getUser()->getAccount();
        if ($account->getId() != $post->getAccount()->getId()) {
	        return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $post_title = $post->getTitle();
        
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->getRequest();
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            if ($form->isValid()) {
                $em->remove($post);
                $em->flush();
                
                $event = new PostPostEvent($securityContext, $post, $em);
                $this->get('event_dispatcher')->dispatch(PostEvents::onPostPost, $event);
                $event->categoryDisplay();
                
                $this->get('session')->getFlashBag()->add('success', 'L\'article « '.$post_title.' » a été supprimé.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_post_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'post' => $post,
        );
    }
}
