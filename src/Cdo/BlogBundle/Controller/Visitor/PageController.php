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
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }
        
        return array(
            'page' => $page,
            'tab_active' => $page,
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
    	$cdo_page_sidemenu = $this->container->get('cdo_site.twig.globals_extension')->getGlobals()['cdo_page']['sidemenu'];
        
        $page = $em->getRepository('CdoBlogBundle:Page')
                   ->getBySlug($account, $slug);
        if (!$page) {
            throw $this->createNotFoundException('Cette page n\'existe pas');
        }
        if ($page->getHomepage()) {
            return $this->redirect($this->generateUrl('cdo_blog_visitor_page_homepage', array(
                'subdomain' => $subdomain,
            )));
        }
        
        $display_tree = false;
        $page_root = $page;
        if ($cdo_page_sidemenu != 'none') {
            if ($page->getParent()) {
                $page_root = $page->getParent();
                if ($page_root->getParent()) {
                    $page_root = $page_root->getParent();
                }
                $display_tree = true;
            } else {
                $page_collection = $em->getRepository('CdoBlogBundle:Page')
                                      ->hasChildrenDisplay($page);
                if ($page_collection) {
                    $display_tree = true;
                }
            }
        }
        
        $args = array(
            'page' => $page,
            'tab_active' => $page_root,
            'cdo_page_sidemenu' => $cdo_page_sidemenu,
        );
        
        if ($display_tree) {
            return $this->render('CdoBlogBundle:Visitor/Page:show_tree.html.twig', $args);
        } else {
            return $args;
        }
    }
}
