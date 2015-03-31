<?php

namespace Cdo\SiteBundle\Listener;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Security\Core\SecurityContext as Context;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class SubdomainListener extends \Twig_Extension
{
    protected $container;
    protected $context;
    private $router;
    
    public function __construct(Container $container, Context $context, UrlGeneratorInterface $router)
    {
        $this->container = $container;
        $this->context = $context;
        $this->router = $router;
    }
    
    public function onKernelRequest(GetResponseEvent $event)
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
                    $event->setResponse(new RedirectResponse($this->router->generate('ptm_site_visitor_error_accessdenied')));
                }
            }
        }
    }
    
    public function getName()
    {
        return 'listener.subdomain_listener';
    }
}