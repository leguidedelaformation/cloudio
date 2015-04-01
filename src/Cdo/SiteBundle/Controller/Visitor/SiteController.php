<?php

namespace Cdo\SiteBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SiteController extends Controller
{
    /**
     * @Template()
     */
    public function navbarAction($tab_active, $subdomain)
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
            'tab_active' => $tab_active,
        );
    }
}
