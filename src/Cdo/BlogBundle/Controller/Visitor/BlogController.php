<?php

namespace Cdo\BlogBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_visitor_blog_index")
     * @Template()
     */
    public function indexAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        
        $post_collection = $em->getRepository('CdoBlogBundle:Post')
                              ->getDisplay($account);
        if ($post_collection == null) {
            return $this->redirect($this->generateUrl('cdo_site_visitor_error_notfound', array(
                'subdomain' => $subdomain,
            )));
        }
        
        $category_collection = $em->getRepository('CdoBlogBundle:Category')
                                  ->getDisplay($account);
        
        return array(
            'post_collection' => $post_collection,
            'category_collection' => $category_collection,
//            'placement' => 'tree_content',
        );
    }
}
