<?php

namespace Ptm\SiteBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/error")
 */
class ErrorController extends Controller
{
    /**
     * @Route("/accessdenied", name="ptm_site_visitor_error_accessdenied")
     * @Template()
     */
    public function accessdeniedAction()
    {
        return array();
    }
}
