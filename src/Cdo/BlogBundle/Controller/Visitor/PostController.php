<?php

namespace Cdo\BlogBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
        
        return array(
            'post' => $post,
            'category_collection' => $category_collection,
            'post_category_collection' => $post_category_collection,
        );
    }
}
