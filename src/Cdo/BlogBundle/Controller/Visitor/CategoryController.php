<?php

namespace Cdo\BlogBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/category")
 */
class CategoryController extends Controller
{
    /**
     * @Route("/{slug}", name="cdo_blog_visitor_category_index")
     * @Template()
     */
    public function indexAction($slug, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $category_rep = $em->getRepository('CdoBlogBundle:Category');
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        
        $category = $category_rep->getBySlugDisplay($account, $slug);
        if (!$category) {
            return $this->redirect($this->generateUrl('cdo_site_visitor_error_notfound', array(
                'subdomain' => $subdomain,
            )));
        }
        
        $post_collection = $em->getRepository('CdoBlogBundle:Post')
                              ->getByCategoryDisplay($account, $category);
        
        $category_collection = $category_rep->getDisplay($account);
        
        return array(
            'category' => $category,
            'post_collection' => $post_collection,
            'category_collection' => $category_collection,
        );
    }
}
