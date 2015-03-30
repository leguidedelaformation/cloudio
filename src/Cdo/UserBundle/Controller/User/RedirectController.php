<?php

namespace Cdo\UserBundle\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class RedirectController extends Controller
{
    /**
     * @Route("/redirect", name="cdo_user_user_redirect_login")
     */
    public function loginAction()
    {
        $user = $this->getUser();
        $securityContext = $this->container->get('security.context');
        
        if ($securityContext->isGranted('ROLE_USER'))
        {
        	$account = $user->getAccount();
        	
        	return $this->redirect($this->generateUrl('cdo_site_admin_site_dashboard', array(
        	    'subdomain' => $account->getSubdomain(),
        	)));
        } else {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }
    }
}
