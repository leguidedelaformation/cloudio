<?php

namespace Cdo\SiteBundle\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class ParamsExtension extends \Twig_Extension
{
    protected $container;
    protected $doctrine;
    
    public function __construct(Container $container, RegistryInterface $doctrine)
    {
        $this->container = $container;
        $this->doctrine = $doctrine;
    }

    public function encode($subdomain)
    {
        $em = $this->doctrine->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        
        $params_array = array(
    	    'site' => array(
    	        'title' => $account->getTitle(),
    	        'slogan' => 'Le meilleur de la formation',
    	    ),
    	    'social' => array(
    	        'color' => 'lightgrey',
    	        'facebook' => '#',
    	        'twitter' => '#',
    	        'google' => '#',
    	        'linkedin' => '#',
    	    ),
    	    'page' => array(
    	        'placement' => '_tree_content',
    	    ),
    	    'blog' => array(
    	        'title' => 'Blog',
    	        'menurank' => 1,
    	        'placement' => '_tree_content',
    	    ),
    	);
        
        $params_encoded = json_encode($params_array, JSON_PRETTY_PRINT);
        
        $directory = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain;

        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }
        $path = $directory.'/params.json';
        $params_file = fopen($path, 'w') or die("Unable to open file!");
        fwrite($params_file, $params_encoded);
        return fclose($params_file);
    }
    
    public function getName()
    {
        return 'params_extension';
    }
}