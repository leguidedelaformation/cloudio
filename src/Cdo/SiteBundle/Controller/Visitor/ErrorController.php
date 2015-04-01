<?php

namespace Cdo\SiteBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/error")
 */
class ErrorController extends Controller
{
    /**
     * @Route("/notfound", name="cdo_site_visitor_error_notfound")
     * @Template()
     */
    public function notfoundAction($subdomain)
    {
        return array();
    }
}
