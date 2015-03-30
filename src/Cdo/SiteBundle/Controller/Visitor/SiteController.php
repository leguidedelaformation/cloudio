<?php

namespace Cdo\SiteBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SiteController extends Controller
{
    public function content($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        $menu_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/menu.json';
        
        $navbarlogo = $em->getRepository('CdoMediaBundle:Image')
                         ->findNavbarlogo($account);
        $link_array = json_decode(file_get_contents($menu_path), true);
        
        return array(
            'navbarlogo' => $navbarlogo,
            'link_array' => $link_array,
        );
    }
    
    /**
     * @Template()
     */
    public function navbarAction($subdomain)
    {
        return self::content($subdomain);
    }
    
    public function navbarPageAction($tab_active, $subdomain)
    {
        $navbar_data = self::content($subdomain);
        
        return $this->render('CdoSiteBundle:Visitor/Site:navbar.html.twig', array(
            'navbarlogo' => $navbar_data['navbarlogo'],
            'link_array' => $navbar_data['link_array'],
            'tab_active' => $tab_active,
        ));
    }
}
