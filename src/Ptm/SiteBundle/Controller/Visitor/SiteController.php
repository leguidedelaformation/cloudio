<?php

namespace Ptm\SiteBundle\Controller\Visitor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SiteController extends Controller
{
    /**
     * @Route("/", name="ptm_site_visitor_site_homepage")
     * @Template()
     */
    public function homepageAction()
    {
        $em = $this->getDoctrine()->getManager();
        $account_list = $em->getRepository('CdoAccountBundle:Account')
                           ->findAll();
        
        $this->get('session')->remove('_security.main.target_path');
        
        return array(
            'account_list' => $account_list,
        );
    }
}
