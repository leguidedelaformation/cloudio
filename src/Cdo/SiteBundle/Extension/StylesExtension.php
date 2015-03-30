<?php

namespace Cdo\SiteBundle\Extension;

use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class StylesExtension extends \Twig_Extension
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
        
        $styles_array = array(
    	    'body' => array(
    	        'width' => null,
    	        'background' => null,
    	        'backgroundimage' => null,
    	    ),
    	    'wrap' => array(
    	        'width' => null,
    	        'background' => null,
    	        'color' => null,
    	        'fontsize' => null,
    	        'margintop' => null,
    	        'paddingtop' => null,
    	        'containerfluid' => array(
    	            'paddingtop' => null,
    	            'paddingright' => null,
    	            'paddingbottom' => null,
    	            'paddingleft' => null,
    	        ),
    	    ),
    	    'navbar' => array(
    	        'background' => null,
    	        'backgroundimage' => null,
    	        'borderradius' => null,
    	        'boxshadow' => null,
    	        'logo' => array(
    	            'img' => array(
    	                'maxheight' => null,
    	                'maxwidth' => null,
    	            ),
    	        ),
    	        'navbarsup' => array(
    	            'paddingtop' => null,
    	            'paddingbottom' => null,
    	        ),
    	        'slogan' => array(
    	            'fontsize' => null,
    	            'fontweight' => null,
    	            'fontstyle' => null,
    	            'color' => null,
	                'textshadow' => null,
    	        ),
    	        'collapse' => array(
	                'background' => null,
    	            'borderradius' => null,
    	        ),
    	        'navbarcollapse' => array(
	                'textalign' => null,
	                'paddingleft' => null,
	                'paddingright' => null,
    	        ),
    	        'nav' => array(
	                'li' => array(
	                    'a' => array(
	                        'color' => null,
	                        'textshadow' => null,
	                        'hover' => array(
	                            'background' => null,
	                            'borderradius' => null,
	                            'color' => null,
	                            'textshadow' => null,
	                            'boxshadow' => null,
	                        ),
	                    ),
	                    'active' => array(
	                        'a' => array(
	                            'background' => null,
	                            'borderradius' => null,
	                            'color' => null,
	                            'textshadow' => null,
	                            'boxshadow' => null,
	                        ),
	                    ),
	                ),
    	        ),
    	        'dropdownmenu' => array(
	                'li' => array(
	                    'a' => array(
	                        'hover' => array(
	                            'background' => null,
	                            'color' => null,
	                        ),
	                    ),
	                ),
    	        ),
    	    ),
    	    'pagemenu' => array(
    	        'background' => null,
    	        'borderradius' => null,
    	        'boxshadow' => null,
    	        'lineheight' => null,
    	        'paddingtop' => null,
    	        'paddingbottom' => null,
    	        'margintop' => null,
    	        'marginbottom' => null,
    	        'ul' => array(
    	            'li' => array(
    	                'fontsize' => null,
    	                'a' => array(
    	                    'color' => null,
    	                ),
    	            ),
	                'ul' => array(
	                    'li' => array(
                            'fontsize' => null,
                            'a' => array(
                                'color' => null,
                            ),
	                    ),
	                ),
    	        ),
    	    ),
    	    'pagecontent' => array(
	            'paddingright' => null,
	            'paddingleft' => null,
    	    ),
    	);
        
        $styles_encoded = json_encode($styles_array, JSON_PRETTY_PRINT);
        
        $directory = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain;

        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }
        $path = $directory.'/styles.json';
        $styles_file = fopen($path, 'w') or die("Unable to open file!");
        fwrite($styles_file, $styles_encoded);
        return fclose($styles_file);
    }

    public function generate($subdomain)
    {
        $em = $this->doctrine->getManager();
        $account = $em->getRepository('CdoAccountBundle:Account')->findSubdomain($subdomain);
        
        $styles_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/styles.json';
        
        $styles_array = json_decode(file_get_contents($styles_path), true);
        
        $web_directory = 'css/custom/'.$subdomain;
        if (!file_exists($web_directory)) {
            mkdir($web_directory, 0775, true);
        }
        $web_path = $web_directory.'/styles.css';
        
        $content = $this->container->get('templating')->render('CdoSiteBundle:Admin/Styles:generate.html.twig', array(
            'styles_array' => $styles_array,
        ));
        
        $handle = fopen($web_path, "w") or die("Unable to open file!");
        ftruncate($handle, 0);
        return fputs($handle, $content);
    }
    
    public function getName()
    {
        return 'styles_extension';
    }
}