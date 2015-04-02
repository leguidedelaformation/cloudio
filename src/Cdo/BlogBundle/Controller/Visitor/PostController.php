<?php

namespace Cdo\BlogBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Cdo\BlogBundle\Entity\Comment;
use Cdo\ClientBundle\Entity\Individual;
use Cdo\BlogBundle\Form\Visitor\Comment\CommentType;

/**
 * @Route("/blog")
 */
class PostController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_visitor_post_index")
     * @Template()
     */
    public function indexAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        
        $post_collection = $em->getRepository('CdoBlogBundle:Post')
                              ->getDisplay($account);
        
        $category_collection = $em->getRepository('CdoBlogBundle:Category')
                                  ->getDisplay($account);
        
        return array(
            'post_collection' => $post_collection,
            'category_collection' => $category_collection,
        );
    }
    
    /**
     * @Route("/{slug}", name="cdo_blog_visitor_post_show")
     * @Template()
     */
    public function showAction($slug, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $category_rep = $em->getRepository('CdoBlogBundle:Category');
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        
        $post = $em->getRepository('CdoBlogBundle:Post')
                   ->getBySlugDisplay($account, $slug);
        if (!$post) {
            return $this->redirect($this->generateUrl('cdo_site_visitor_error_notfound', array(
                'subdomain' => $subdomain,
            )));
        }
        
        $category_collection = $category_rep->getDisplay($account);
        $post_category_collection = $category_rep->getByPostDisplay($account, $post);
        $comment_collection = $em->getRepository('CdoBlogBundle:Comment')
                                 ->getByPostDisplay($post);
        
        $comment = new Comment;
        $comment->setAccount($account);
        $comment->setPost($post);
        $individual = new Individual;
        $individual->setAccount($account);
        $individual->setOrigin(1);
        $comment->setIndividual($individual);
//        $comment->setUser($this->getUser());
        
        $form = $this->createForm(new CommentType, $comment);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                $em->persist($comment);
                $em->flush();
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_comment_confirm', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'post' => $post,
            'category_collection' => $category_collection,
            'post_category_collection' => $post_category_collection,
            'comment_collection' => $comment_collection,
        );
    }
}
