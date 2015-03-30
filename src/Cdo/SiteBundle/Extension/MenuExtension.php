<?php

namespace Cdo\SiteBundle\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class MenuExtension extends \Twig_Extension
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
        $page_array = $this->container->get('cdo_blog.twig.page_extension')->pageListByLevel($subdomain, true)['page_array'];
        
        $menu_encoded = json_encode($page_array, JSON_PRETTY_PRINT);
        
        $directory = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain;

        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }
        $path = $directory.'/menu.json';
        $menu_file = fopen($path, 'w') or die("Unable to open file!");
        fwrite($menu_file, $menu_encoded);
        return fclose($menu_file);
    }
    
    public function getName()
    {
        return 'navbar_extension';
    }
}