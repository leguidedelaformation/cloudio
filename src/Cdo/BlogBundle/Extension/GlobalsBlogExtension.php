<?php

namespace Cdo\BlogBundle\Extension;

class GlobalsBlogExtension extends \Twig_Extension
{
    public function getGlobals()
    {
        $cdo_blog_comment_status = $GLOBALS['kernel']->getContainer()->getParameter('cdo_blog_comment_status');
        
        return array(
            'cdo_blog_comment_status' => $cdo_blog_comment_status,
        );
    }
    
    public function getName()
    {
        return 'globals_blog_extension';
    }
}