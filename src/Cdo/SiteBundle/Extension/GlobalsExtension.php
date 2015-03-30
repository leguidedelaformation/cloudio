<?php

namespace Cdo\SiteBundle\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpFoundation\RequestStack;

class GlobalsExtension extends \Twig_Extension
{
    protected $container;
    protected $request;
    
    public function __construct(Container $container, RequestStack $request_stack)
    {
        $this->container = $container;
        $this->request = $request_stack->getCurrentRequest();
    }
    
    public function getGlobals()
    {
        $subdomain = $this->request->getSession()->get('subdomain');
        if (!$subdomain) {
        	return array();
        }
        
        $params_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/params.json';
        if (!file_exists($params_path)) {
        	return array();
        }
        $params_array = json_decode(file_get_contents($params_path), true);
        
        return array(
            'cdo_site' => $params_array['site'],
            'cdo_social' => $params_array['social'],
            'cdo_page' => $params_array['page'],
        );
    }
    
    public function getName()
    {
        return 'globals_extension';
    }
}