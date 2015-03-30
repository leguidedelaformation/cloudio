<?php

namespace Cdo\SiteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/site")
 */
class SiteController extends Controller
{
    /**
     * @Route("/", name="cdo_site_admin_site_dashboard")
     * @Template()
     */
    public function dashboardAction($subdomain)
    {
        return array();
    }
}
