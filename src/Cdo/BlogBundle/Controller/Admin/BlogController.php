<?php

namespace Cdo\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * @Route("/admin/blog")
 */
class BlogController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_admin_blog_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction($subdomain)
    {
        return array();
    }
}
