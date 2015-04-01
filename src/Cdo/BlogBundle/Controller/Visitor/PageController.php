<?php

namespace Cdo\BlogBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PageController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_visitor_page_homepage")
     * @Template()
     */
    public function homepageAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        
        $page = $em->getRepository('CdoBlogBundle:Page')
                   ->getByHomepage($account);
        if (!$page) {
            return $this->redirect($this->generateUrl('cdo_site_visitor_error_notfound', array(
                'subdomain' => $subdomain,
            )));
        }
        
        return array(
            'page' => $page,
            'tab_active' => $page->getTitle(),
        );
    }
    
    /**
     * @Route("/{slug}", name="cdo_blog_visitor_page_show")
     * @Template()
     */
    public function showAction($slug, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
    	$placement = $this->container->get('cdo_site.twig.globals_extension')->getGlobals()['cdo_page']['placement'];
        
        $page = $em->getRepository('CdoBlogBundle:Page')
                   ->getBySlugDisplay($account, $slug);
        if (!$page) {
            return $this->redirect($this->generateUrl('cdo_site_visitor_error_notfound', array(
                'subdomain' => $subdomain,
            )));
        }
        if ($page->getHomepage()) {
            return $this->redirect($this->generateUrl('cdo_blog_visitor_page_homepage', array(
                'subdomain' => $subdomain,
            )));
        }
        
        $page_root = $page;
        if (strpos($placement,'_tree') !== false) {
            if ($page->getParent()) {
                $page_root = $page->getParent();
                if ($page_root->getParent()) {
                    $page_root = $page_root->getParent();
                }
            } else {
                $page_collection = $em->getRepository('CdoBlogBundle:Page')
                                      ->hasChildrenDisplay($page);
                if (!$page_collection) {
                    $placement = str_replace('_tree', '', $placement);
                }
            }
        }
        
        return array(
            'page' => $page,
            'page_root' => $page_root,
            'placement' => $placement,
        );
    }
}
