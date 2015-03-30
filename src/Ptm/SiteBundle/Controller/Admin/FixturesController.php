<?php

namespace Ptm\SiteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/admin/fixtures")
 */
class FixturesController extends Controller
{
    /**
     * @Route("/unlink", name="ptm_site_admin_fixtures_unlink")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function unlinkAction()
    {
        $em = $this->getDoctrine()->getManager();
        $page_collection = $em->getRepository('CdoBlogBundle:Page')
                              ->findAll();
        
        foreach ($page_collection as $page) {
            $page->setParent(null);
//            foreach ($page->getChildren() as $$child) {
//            	$page->removeChild($child);
//            }
        }
        
        $em->flush();
        
        return $this->redirect($this->generateUrl('ptm_site_visitor_site_homepage'));
    }
    
    /**
     * @Route("/load", name="ptm_site_admin_fixtures_load")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function loadAction()
    {
        $em = $this->getDoctrine()->getManager();
        $account_list = $em->getRepository('CdoAccountBundle:Account')
                           ->findAll();
        
        foreach ($account_list as $account) {
            $this->container->get('cdo_site.twig.menu_extension')->encode($account->getSubdomain());
            $this->container->get('cdo_site.twig.params_extension')->encode($account->getSubdomain());
            $this->container->get('cdo_site.twig.styles_extension')->encode($account->getSubdomain());
            $this->container->get('cdo_site.twig.styles_extension')->generate($account->getSubdomain());
        }
        
        return $this->redirect($this->generateUrl('ptm_site_visitor_site_homepage'));
    }
}
