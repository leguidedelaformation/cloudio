<?php

namespace Cdo\SiteBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin/params")
 */
class ParamsController extends Controller
{
    /**
     * @Route("/", name="cdo_site_admin_params_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction($subdomain)
    {
        return array();
    }
    
    /**
     * @Route("/site", name="cdo_site_admin_params_site")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function siteAction(Request $request, $subdomain)
    {
        $params_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/params.json';
        
        $params_array = json_decode(file_get_contents($params_path), true);
        
        $fields_array = array(
            'site_title' => $params_array['site']['title'],
            'site_slogan' => $params_array['site']['slogan'],
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('site_title', 'text', array(
                         'label' => 'Titre du site',
                         'required' => false,
                     ))
                     ->add('site_slogan', 'text', array(
                         'label' => 'Slogan',
                         'required' => false,
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $params_array['site']['title'] = $data['site_title'];
            $params_array['site']['slogan'] = $data['site_slogan'];
            $params_encoded = json_encode($params_array, JSON_PRETTY_PRINT);
            $params_file = fopen($params_path, 'w') or die("Unable to open file!");
            fwrite($params_file, $params_encoded);
            fclose($params_file);
                
            $this->get('session')->getFlashBag()->add('success', 'Les paramètres ont été mis à jour.');
            
            return $this->redirect($this->generateUrl('cdo_site_admin_params_index', array(
                'subdomain' => $subdomain,
            )));
        }        

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/social", name="cdo_site_admin_params_social")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function socialAction(Request $request, $subdomain)
    {
        $params_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/params.json';
        
        $params_array = json_decode(file_get_contents($params_path), true);
        
        $fields_array = array(
            'social_color' => $params_array['social']['color'],
            'social_facebook' => $params_array['social']['facebook'],
            'social_twitter' => $params_array['social']['twitter'],
            'social_google' => $params_array['social']['google'],
            'social_linkedin' => $params_array['social']['linkedin'],
        );
        $color_array = array(
            'color' => 'couleurs originales',
            'inverted' => 'blanc',
            'lightgrey' => 'gris clair',
            'grey' => 'gris foncé',
            'black' => 'noir',
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('social_color', 'choice', array(
                         'choices' => $color_array,
                         'label' => 'Couleur des icônes',
                         'required' => false,
                     ))
                     ->add('social_facebook', 'text', array(
                         'label' => 'Adresse Facebook',
                         'required' => false,
                     ))
                     ->add('social_twitter', 'text', array(
                         'label' => 'Adresse Twitter',
                         'required' => false,
                     ))
                     ->add('social_google', 'text', array(
                         'label' => 'Adresse Google+',
                         'required' => false,
                     ))
                     ->add('social_linkedin', 'text', array(
                         'label' => 'Adresse LinkedIn',
                         'required' => false,
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $params_array['social']['color'] = $data['social_color'];
            $params_array['social']['facebook'] = $data['social_facebook'];
            $params_array['social']['twitter'] = $data['social_twitter'];
            $params_array['social']['google'] = $data['social_google'];
            $params_array['social']['linkedin'] = $data['social_linkedin'];
            $params_encoded = json_encode($params_array, JSON_PRETTY_PRINT);
            $params_file = fopen($params_path, 'w') or die("Unable to open file!");
            fwrite($params_file, $params_encoded);
            fclose($params_file);
                
            $this->get('session')->getFlashBag()->add('success', 'Les paramètres ont été mis à jour.');
            
            return $this->redirect($this->generateUrl('cdo_site_admin_params_index', array(
                'subdomain' => $subdomain,
            )));
        }        

        return array(
            'form' => $form->createView(),
        );
    }
    
    /**
     * @Route("/page", name="cdo_site_admin_params_page")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function pageAction(Request $request, $subdomain)
    {
        $params_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/params.json';
        
        $params_array = json_decode(file_get_contents($params_path), true);
        
        $fields_array = array(
            'page_sidemenu' => $params_array['page']['sidemenu'],
        );
        $sidemenu_array = array(
            'left' => 'gauche',
            'right' => 'droite',
            'none' => 'aucun',
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('page_sidemenu', 'choice', array(
                         'choices' => $sidemenu_array,
                         'label' => 'Position du menu latéral',
                         'expanded' => true,
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $params_array['page']['sidemenu'] = $data['page_sidemenu'];
            $params_encoded = json_encode($params_array, JSON_PRETTY_PRINT);
            $params_file = fopen($params_path, 'w') or die("Unable to open file!");
            fwrite($params_file, $params_encoded);
            fclose($params_file);
                
            $this->get('session')->getFlashBag()->add('success', 'Les paramètres ont été mis à jour.');
            
            return $this->redirect($this->generateUrl('cdo_site_admin_params_index', array(
                'subdomain' => $subdomain,
            )));
        }        

        return array(
            'form' => $form->createView(),
        );
    }
}
