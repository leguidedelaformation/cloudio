<?php

namespace Cdo\SiteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/admin/menu")
 */
class MenuController extends Controller
{
    /**
     * @Route("/encode", name="cdo_site_admin_menu_encode")
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function encodeAction($subdomain)
    {
        $this->container->get('cdo_site.twig.menu_extension')->encode($subdomain);
        
        return $this->redirect($this->generateUrl('cdo_blog_visitor_page_homepage', array(
            'subdomain' => $subdomain,
        )));
    }
}
