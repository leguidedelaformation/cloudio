<?php

namespace Cdo\SiteBundle\Listener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\SecurityContext as Context;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class SubdomainListener extends \Twig_Extension
{
    protected $container;
    protected $context;
    
    public function __construct(Container $container, Context $context)
    {
        $this->container = $container;
        $this->context = $context;
    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        $route  = $request->attributes->get('_route');
        $params = $request->attributes->get('_route_params');
        
        if ($params) {
	        if (array_key_exists('subdomain', $params)) {
                $session = $this->container->get('session');
                $session->set('subdomain', $params['subdomain']);
                $session->save();
                
  	            if (strpos($route, 'admin') != null) {
	                $subdomain_route = $params['subdomain'];
	                if ($this->context->isGranted('ROLE_ACCOUNT')) {
	                    $user = $this->context->getToken()->getUser();
	                    $subdomain_user = $user->getAccount()->getSubdomain();
	                    
	                    if ($subdomain_route == $subdomain_user) {
	                        return;
	                    }
	                }
	                throw new AccessDeniedException();
	            }
	        }
        }
    }
    
    public function getName()
    {
        return 'listener.subdomain_listener';
    }
}