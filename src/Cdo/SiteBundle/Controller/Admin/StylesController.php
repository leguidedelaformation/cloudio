<?php

namespace Cdo\SiteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/styles")
 */
class StylesController extends Controller
{
    public function process($styles_array, $styles_path, $subdomain)
    {
        $styles_encoded = json_encode($styles_array, JSON_PRETTY_PRINT);
        $styles_file = fopen($styles_path, 'w') or die("Unable to open file!");
        fwrite($styles_file, $styles_encoded);
        fclose($styles_file);
        
        $this->container->get('cdo_site.twig.styles_extension')->generate($subdomain);
        
        return $this->redirect($this->generateUrl('cdo_site_admin_styles_index', array(
            'subdomain' => $subdomain,
        )));
    }
    
    /**
     * @Route("/generate", name="cdo_site_admin_styles_generate")
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function generateAction($subdomain)
    {
        $this->container->get('cdo_site.twig.styles_extension')->generate($subdomain);
        
        return $this->redirect($this->generateUrl('cdo_site_admin_site_dashboard', array(
            'subdomain' => $subdomain,
        )));
    }
    
    /**
     * @Route("/", name="cdo_site_admin_styles_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction($subdomain)
    {
        return array();
    }
    
    /**
     * @Route("/body", name="cdo_site_admin_styles_body")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function bodyAction(Request $request, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        $image_list = $em->getRepository('CdoMediaBundle:Image')
                         ->getAllOrderedAlt($account);
        $image_array = array();
        foreach ($image_list as $image) {
        	$image_array[$image->getWebpath()] = $image->getAlt();
        }

        $styles_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/styles.json';
        
        $styles_array = json_decode(file_get_contents($styles_path), true);
        
        $fields_array = array(
            'body_width' => $styles_array['body']['width'],
            'body_background' => $styles_array['body']['background'],
            'body_backgroundimage' => $styles_array['body']['backgroundimage'],
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('body_width', 'number', array(
                         'label' => 'Largeur du site',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('body_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                     ))
                     ->add('body_backgroundimage', 'choice', array(
                         'choices' => $image_array,
                         'label' => 'Image de fond :',
                         'required' => false,
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $styles_array['body']['width'] = $data['body_width'];
            $styles_array['body']['background'] = $data['body_background'];
            $styles_array['body']['backgroundimage'] = $data['body_backgroundimage'];
            
            return self::process($styles_array, $styles_path, $subdomain);
        }        

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/header", name="cdo_site_admin_styles_header")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function headerAction(Request $request, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        $image_list = $em->getRepository('CdoMediaBundle:Image')
                         ->getAllOrderedAlt($account);
        $image_array = array();
        foreach ($image_list as $image) {
        	$image_array[$image->getWebpath()] = $image->getAlt();
        }

        $styles_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/styles.json';
        
        $styles_array = json_decode(file_get_contents($styles_path), true);
        
        $fields_array = array(
            'navbar_background' => $styles_array['navbar']['background'],
            'navbar_backgroundimage' => $styles_array['navbar']['backgroundimage'],
            'navbar_borderradius' => $styles_array['navbar']['borderradius'],
            'navbar_boxshadow' => $styles_array['navbar']['boxshadow'],
            'navbar_logo_img_maxheight' => $styles_array['navbar']['logo']['img']['maxheight'],
            'navbar_logo_img_maxwidth' => $styles_array['navbar']['logo']['img']['maxwidth'],
            'navbar_navbarsup_paddingtop' => $styles_array['navbar']['navbarsup']['paddingtop'],
            'navbar_navbarsup_paddingbottom' => $styles_array['navbar']['navbarsup']['paddingbottom'],
            'navbar_slogan_fontsize' => $styles_array['navbar']['slogan']['fontsize'],
            'navbar_slogan_fontweight' => $styles_array['navbar']['slogan']['fontweight'],
            'navbar_slogan_fontstyle' => $styles_array['navbar']['slogan']['fontstyle'],
            'navbar_slogan_color' => $styles_array['navbar']['slogan']['color'],
            'navbar_slogan_textshadow' => $styles_array['navbar']['slogan']['textshadow'],
        );
        $fontweight_array = array(
            'bold' => 'oui',
        );
        $fontstyle_array = array(
            'italic' => 'oui',
        );
        $textshadow_array = array(
            '1px 1px 0px rgba(0,0,0,.5)' => 'gris',
        );
        $boxshadow_array = array(
            '0 2px 2px rgba(0, 0, 0, 0.25)' => 'pâle',
            '0 2px 2px rgba(0, 0, 0, 0.5)' => 'foncé',
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('navbar_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_backgroundimage', 'choice', array(
                         'choices' => $image_array,
                         'label' => 'Image de fond :',
                         'required' => false,
                     ))
                     ->add('navbar_borderradius', 'number', array(
                         'label' => 'Coins arrondis',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_boxshadow', 'choice', array(
                         'choices' => $boxshadow_array,
                         'label' => 'Ombre portée',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->add('navbar_logo_img_maxheight', 'number', array(
                         'label' => 'Hauteur max. du logo',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_logo_img_maxwidth', 'number', array(
                         'label' => 'Largeur max. du logo',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_navbarsup_paddingtop', 'number', array(
                         'label' => 'Hauteur de l\'en-tête (partie haute)',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_navbarsup_paddingbottom', 'number', array(
                         'label' => 'Hauteur de l\'en-tête (partie basse)',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_slogan_fontsize', 'number', array(
                         'label' => 'Taille de police du slogan',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_slogan_fontweight', 'choice', array(
                         'choices' => $fontweight_array,
                         'label' => 'Style de police : gras',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'non',
                     ))
                     ->add('navbar_slogan_fontstyle', 'choice', array(
                         'choices' => $fontstyle_array,
                         'label' => 'Style de police : italique',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'non',
                     ))
                     ->add('navbar_slogan_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_slogan_textshadow', 'choice', array(
                         'choices' => $textshadow_array,
                         'label' => 'Ombre portée de police',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $styles_array['navbar']['background'] = $data['navbar_background'];
            $styles_array['navbar']['backgroundimage'] = $data['navbar_backgroundimage'];
            $styles_array['navbar']['borderradius'] = $data['navbar_borderradius'];
            $styles_array['navbar']['boxshadow'] = $data['navbar_boxshadow'];
            $styles_array['navbar']['logo']['img']['maxheight'] = $data['navbar_logo_img_maxheight'];
            $styles_array['navbar']['logo']['img']['maxwidth'] = $data['navbar_logo_img_maxwidth'];
            $styles_array['navbar']['navbarsup']['paddingtop'] = $data['navbar_navbarsup_paddingtop'];
            $styles_array['navbar']['navbarsup']['paddingbottom'] = $data['navbar_navbarsup_paddingbottom'];
            $styles_array['navbar']['slogan']['fontsize'] = $data['navbar_slogan_fontsize'];
            $styles_array['navbar']['slogan']['fontweight'] = $data['navbar_slogan_fontweight'];
            $styles_array['navbar']['slogan']['fontstyle'] = $data['navbar_slogan_fontstyle'];
            $styles_array['navbar']['slogan']['color'] = $data['navbar_slogan_color'];
            $styles_array['navbar']['slogan']['textshadow'] = $data['navbar_slogan_textshadow'];
            
            return self::process($styles_array, $styles_path, $subdomain);
        }        

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/navbar", name="cdo_site_admin_styles_navbar")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function navbarAction(Request $request, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();

        $styles_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/styles.json';
        
        $styles_array = json_decode(file_get_contents($styles_path), true);
        
        $fields_array = array(
            'navbar_collapse_background' => $styles_array['navbar']['collapse']['background'],
            'navbar_collapse_borderradius' => $styles_array['navbar']['collapse']['borderradius'],
            'navbar_navbarcollapse_textalign' => $styles_array['navbar']['navbarcollapse']['textalign'],
            'navbar_navbarcollapse_paddingleft' => $styles_array['navbar']['navbarcollapse']['paddingleft'],
            'navbar_navbarcollapse_paddingright' => $styles_array['navbar']['navbarcollapse']['paddingright'],
            'navbar_nav_li_a_color' => $styles_array['navbar']['nav']['li']['a']['color'],
            'navbar_nav_li_a_textshadow' => $styles_array['navbar']['nav']['li']['a']['textshadow'],
            'navbar_nav_li_a_hover_background' => $styles_array['navbar']['nav']['li']['a']['hover']['background'],
            'navbar_nav_li_a_hover_borderradius' => $styles_array['navbar']['nav']['li']['a']['hover']['borderradius'],
            'navbar_nav_li_a_hover_color' => $styles_array['navbar']['nav']['li']['a']['hover']['color'],
            'navbar_nav_li_a_hover_textshadow' => $styles_array['navbar']['nav']['li']['a']['hover']['textshadow'],
            'navbar_nav_li_a_hover_boxshadow' => $styles_array['navbar']['nav']['li']['a']['hover']['boxshadow'],
            'navbar_nav_li_active_a_background' => $styles_array['navbar']['nav']['li']['active']['a']['background'],
            'navbar_nav_li_active_a_borderradius' => $styles_array['navbar']['nav']['li']['active']['a']['borderradius'],
            'navbar_nav_li_active_a_color' => $styles_array['navbar']['nav']['li']['active']['a']['color'],
            'navbar_nav_li_active_a_textshadow' => $styles_array['navbar']['nav']['li']['active']['a']['textshadow'],
            'navbar_nav_li_active_a_boxshadow' => $styles_array['navbar']['nav']['li']['active']['a']['boxshadow'],
            'navbar_dropdownmenu_li_a_hover_background' => $styles_array['navbar']['dropdownmenu']['li']['a']['hover']['background'],
            'navbar_dropdownmenu_li_a_hover_color' => $styles_array['navbar']['dropdownmenu']['li']['a']['hover']['color'],
        );
        $textalign_array = array(
            'center' => 'milieu',
            'right' => 'droite',
        );
        $textshadow_array = array(
            '1px 1px 0px rgba(0,0,0,.5)' => 'gris',
        );
        $boxshadow_array = array(
            'inset 0 3px 5px rgba(0, 0, 0, 0.125)' => 'gris',
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('navbar_collapse_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_collapse_borderradius', 'number', array(
                         'label' => 'Coins arrondis',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_navbarcollapse_textalign', 'choice', array(
                         'choices' => $textalign_array,
                         'label' => 'Position du menu',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'gauche',
                     ))
                     ->add('navbar_navbarcollapse_paddingleft', 'number', array(
                         'label' => 'Marge gauche (pour un menu placé à gauche)',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_navbarcollapse_paddingright', 'number', array(
                         'label' => 'Marge droite (pour un menu placé à droite)',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_nav_li_a_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_nav_li_a_textshadow', 'choice', array(
                         'choices' => $textshadow_array,
                         'label' => 'Ombre portée de police',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->add('navbar_nav_li_a_hover_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_nav_li_a_hover_borderradius', 'number', array(
                         'label' => 'Arrondi',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_nav_li_a_hover_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_nav_li_a_hover_textshadow', 'choice', array(
                         'choices' => $textshadow_array,
                         'label' => 'Ombre portée de police',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->add('navbar_nav_li_a_hover_boxshadow', 'choice', array(
                         'choices' => $boxshadow_array,
                         'label' => 'Ombre intérieure',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->add('navbar_nav_li_active_a_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_nav_li_active_a_borderradius', 'number', array(
                         'label' => 'Arrondi',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('navbar_nav_li_active_a_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_nav_li_active_a_textshadow', 'choice', array(
                         'choices' => $textshadow_array,
                         'label' => 'Ombre portée de police',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->add('navbar_nav_li_active_a_boxshadow', 'choice', array(
                         'choices' => $boxshadow_array,
                         'label' => 'Ombre intérieure',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->add('navbar_dropdownmenu_li_a_hover_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('navbar_dropdownmenu_li_a_hover_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $styles_array['navbar']['collapse']['background'] = $data['navbar_collapse_background'];
            $styles_array['navbar']['collapse']['borderradius'] = $data['navbar_collapse_borderradius'];
            $styles_array['navbar']['navbarcollapse']['textalign'] = $data['navbar_navbarcollapse_textalign'];
    	    $styles_array['navbar']['navbarcollapse']['paddingleft'] = null;
    	    $styles_array['navbar']['navbarcollapse']['paddingright'] = null;
            switch ($data['navbar_navbarcollapse_textalign']) {
            	case null:
            	    $styles_array['navbar']['navbarcollapse']['paddingleft'] = $data['navbar_navbarcollapse_paddingleft'];
            	break;
            	case 'right':
            	    $styles_array['navbar']['navbarcollapse']['paddingright'] = $data['navbar_navbarcollapse_paddingright'];
            }
            $styles_array['navbar']['nav']['li']['a']['color'] = $data['navbar_nav_li_a_color'];
            $styles_array['navbar']['nav']['li']['a']['textshadow'] = $data['navbar_nav_li_a_textshadow']
                ? $data['navbar_nav_li_a_textshadow']
                : 'inherit';
            $styles_array['navbar']['nav']['li']['a']['hover']['background'] = $data['navbar_nav_li_a_hover_background'];
            $styles_array['navbar']['nav']['li']['a']['hover']['borderradius'] = $data['navbar_nav_li_a_hover_borderradius'];
            $styles_array['navbar']['nav']['li']['a']['hover']['color'] = $data['navbar_nav_li_a_hover_color'];
            $styles_array['navbar']['nav']['li']['a']['hover']['textshadow'] = $data['navbar_nav_li_a_hover_textshadow']
                ? $data['navbar_nav_li_a_hover_textshadow']
                : 'inherit';
            $styles_array['navbar']['nav']['li']['a']['hover']['boxshadow'] = $data['navbar_nav_li_a_hover_boxshadow']
                ? $data['navbar_nav_li_a_hover_boxshadow']
                : 'inherit';
            $styles_array['navbar']['nav']['li']['active']['a']['background'] = $data['navbar_nav_li_active_a_background'];
            $styles_array['navbar']['nav']['li']['active']['a']['borderradius'] = $data['navbar_nav_li_active_a_borderradius'];
            $styles_array['navbar']['nav']['li']['active']['a']['color'] = $data['navbar_nav_li_active_a_color'];
            $styles_array['navbar']['nav']['li']['active']['a']['textshadow'] = $data['navbar_nav_li_active_a_textshadow']
                ? $data['navbar_nav_li_active_a_textshadow']
                : 'inherit';
            $styles_array['navbar']['nav']['li']['active']['a']['boxshadow'] = $data['navbar_nav_li_active_a_boxshadow']
                ? $data['navbar_nav_li_active_a_boxshadow']
                : 'inherit';
            $styles_array['navbar']['dropdownmenu']['li']['a']['hover']['background'] = $data['navbar_dropdownmenu_li_a_hover_background'];
            $styles_array['navbar']['dropdownmenu']['li']['a']['hover']['color'] = $data['navbar_dropdownmenu_li_a_hover_color'];
            
            return self::process($styles_array, $styles_path, $subdomain);
        }        

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/content", name="cdo_site_admin_styles_content")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function contentAction(Request $request, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();

        $styles_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/styles.json';
        
        $styles_array = json_decode(file_get_contents($styles_path), true);
        
        $fields_array = array(
            'wrap_width' => $styles_array['wrap']['width'],
            'wrap_background' => $styles_array['wrap']['background'],
            'wrap_color' => $styles_array['wrap']['color'],
            'wrap_fontsize' => $styles_array['wrap']['fontsize'],
            'wrap_containerfluid_paddingtop' => $styles_array['wrap']['containerfluid']['paddingtop'],
            'wrap_containerfluid_paddingright' => $styles_array['wrap']['containerfluid']['paddingright'],
            'wrap_containerfluid_paddingbottom' => $styles_array['wrap']['containerfluid']['paddingbottom'],
            'wrap_containerfluid_paddingleft' => $styles_array['wrap']['containerfluid']['paddingleft'],
            'pagemenu_background' => $styles_array['pagemenu']['background'],
            'pagemenu_borderradius' => $styles_array['pagemenu']['borderradius'],
            'pagemenu_boxshadow' => $styles_array['pagemenu']['boxshadow'],
            'pagemenu_lineheight' => $styles_array['pagemenu']['lineheight'],
            'pagemenu_paddingtop' => $styles_array['pagemenu']['paddingtop'],
            'pagemenu_paddingbottom' => $styles_array['pagemenu']['paddingbottom'],
            'pagemenu_margintop' => $styles_array['pagemenu']['margintop'],
            'pagemenu_marginbottom' => $styles_array['pagemenu']['marginbottom'],
            'pagemenu_ul_li_fontsize' => $styles_array['pagemenu']['ul']['li']['fontsize'],
            'pagemenu_ul_li_a_color' => $styles_array['pagemenu']['ul']['li']['a']['color'],
            'pagemenu_ul_ul_li_fontsize' => $styles_array['pagemenu']['ul']['ul']['li']['fontsize'],
            'pagemenu_ul_ul_li_a_color' => $styles_array['pagemenu']['ul']['ul']['li']['a']['color'],
            'pagecontent_paddingright' => $styles_array['pagecontent']['paddingright'],
            'pagecontent_paddingleft' => $styles_array['pagecontent']['paddingleft'],
        );
        $boxshadow_array = array(
            '0 2px 2px rgba(0, 0, 0, 0.25)' => 'pâle',
            '0 2px 2px rgba(0, 0, 0, 0.5)' => 'foncé',
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('wrap_width', 'number', array(
                         'label' => 'Largeur du contenu',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('wrap_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('wrap_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('wrap_fontsize', 'number', array(
                         'label' => 'Taille de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('wrap_containerfluid_paddingtop', 'number', array(
                         'label' => 'Marge haute',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('wrap_containerfluid_paddingright', 'number', array(
                         'label' => 'Marge droite',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('wrap_containerfluid_paddingbottom', 'number', array(
                         'label' => 'Marge basse',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('wrap_containerfluid_paddingleft', 'number', array(
                         'label' => 'Marge gauche',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_background', 'text', array(
                         'label' => 'Couleur de fond',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('pagemenu_borderradius', 'number', array(
                         'label' => 'Coins arrondis',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_boxshadow', 'choice', array(
                         'choices' => $boxshadow_array,
                         'label' => 'Ombre portée',
                         'expanded' => true,
                         'required' => false,
                         'empty_value' => 'pas d\'ombre',
                     ))
                     ->add('pagemenu_lineheight', 'number', array(
                         'label' => 'Hauteur des lignes',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_paddingtop', 'number', array(
                         'label' => 'Marge intérieure haute',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_paddingbottom', 'number', array(
                         'label' => 'Marge intérieure basse',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_margintop', 'number', array(
                         'label' => 'Marge extérieure haute',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_marginbottom', 'number', array(
                         'label' => 'Marge extérieure basse',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_ul_li_fontsize', 'number', array(
                         'label' => 'Taille de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_ul_li_a_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('pagemenu_ul_ul_li_fontsize', 'number', array(
                         'label' => 'Taille de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagemenu_ul_ul_li_a_color', 'text', array(
                         'label' => 'Couleur de police',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$',
                             'title' => 'Code couleur hexadécimal',
                         ),
                     ))
                     ->add('pagecontent_paddingright', 'number', array(
                         'label' => 'Marge droite',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->add('pagecontent_paddingleft', 'number', array(
                         'label' => 'Marge gauche',
                         'required' => false,
                         'attr' => array(
                             'pattern' => '\d+',
                             'title' => 'Nombre entier',
                         ),
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $styles_array['wrap']['width'] = $data['wrap_width'];
            $styles_array['wrap']['background'] = $data['wrap_background'];
            $styles_array['wrap']['color'] = $data['wrap_color'];
            $styles_array['wrap']['fontsize'] = $data['wrap_fontsize'];
            $styles_array['wrap']['containerfluid']['paddingtop'] = $data['wrap_containerfluid_paddingtop'];
            $styles_array['wrap']['containerfluid']['paddingright'] = $data['wrap_containerfluid_paddingright'];
            $styles_array['wrap']['containerfluid']['paddingbottom'] = $data['wrap_containerfluid_paddingbottom'];
            $styles_array['wrap']['containerfluid']['paddingleft'] = $data['wrap_containerfluid_paddingleft'];
            $styles_array['pagemenu']['background'] = $data['pagemenu_background'];
            $styles_array['pagemenu']['borderradius'] = $data['pagemenu_borderradius'];
            $styles_array['pagemenu']['boxshadow'] = $data['pagemenu_boxshadow'];
            $styles_array['pagemenu']['lineheight'] = $data['pagemenu_lineheight'];
            $styles_array['pagemenu']['paddingtop'] = $data['pagemenu_paddingtop'];
            $styles_array['pagemenu']['paddingbottom'] = $data['pagemenu_paddingbottom'];
            $styles_array['pagemenu']['margintop'] = $data['pagemenu_margintop'];
            $styles_array['pagemenu']['marginbottom'] = $data['pagemenu_marginbottom'];
            $styles_array['pagemenu']['ul']['li']['fontsize'] = $data['pagemenu_ul_li_fontsize'];
            $styles_array['pagemenu']['ul']['li']['a']['color'] = $data['pagemenu_ul_li_a_color'];
            $styles_array['pagemenu']['ul']['ul']['li']['fontsize'] = $data['pagemenu_ul_ul_li_fontsize'];
            $styles_array['pagemenu']['ul']['ul']['li']['a']['color'] = $data['pagemenu_ul_ul_li_a_color'];
            $styles_array['pagecontent']['paddingright'] = $data['pagecontent_paddingright'];
            $styles_array['pagecontent']['paddingleft'] = $data['pagecontent_paddingleft'];
            
            return self::process($styles_array, $styles_path, $subdomain);
        }        

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/footer", name="cdo_site_admin_styles_footer")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function footerAction(Request $request, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();

        $styles_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/styles.json';
        
        $styles_array = json_decode(file_get_contents($styles_path), true);
        
        $fields_array = array(
            'wrap_paddingtop' => $styles_array['wrap']['paddingtop'],
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('wrap_paddingtop', 'number', array(
                         'label' => 'Ajustement du pied de page',
                         'required' => false,
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $styles_array['wrap']['paddingtop'] = $data['wrap_paddingtop'];
            $styles_array['wrap']['margintop'] = -$data['wrap_paddingtop'];
            
            return self::process($styles_array, $styles_path, $subdomain);
        }        

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/sheet", name="cdo_site_admin_styles_sheet")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function sheetAction(Request $request, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        
        $directory = 'css/custom/'.$subdomain;
        if (!file_exists($directory)) {
            mkdir($directory, 0775, true);
        }
        $file = $directory.'/sheet.css';
        $content = file_exists($file)
            ? file_get_contents($file)
            : null;
        
        $form = $this->createFormBuilder()
            ->add('content', 'textarea', array(
                'label' => 'Contenu du fichier :',
                'data' => $content,
                'attr' => array(
                    'rows' => '20',
                ),
                'required' => false,
            ))
            ->getForm();
        
        if ($request->getMethod() == 'POST')
        {
        	$form->submit($request);
        	
        	if($form->isValid())
            {
                $postData = current($request->request->all());
                $submitted_content = $postData['content'];
                $handle = fopen($file, "w");
                ftruncate($handle, 0);
                fputs($handle, $submitted_content);
            }
                
            return $this->redirect($this->generateUrl('cdo_site_admin_styles_index', array(
                'subdomain' => $subdomain,
            )));
        }
        
        return array(
            'form' => $form->createView(),
        );
    }
}
