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
            'page_placement' => $params_array['page']['placement'],
        );
        $placement_array = array(
            '_content' => '1 bloc: Contenu',
            '_tree_content' => '2 blocs: Menu à gauche - Contenu à droite',
            '_content_tree' => '2 blocs: Contenu à gauche - Menu à droite',
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('page_placement', 'choice', array(
                         'choices' => $placement_array,
                         'label' => 'Disposition',
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $params_array['page']['placement'] = $data['page_placement'];
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
     * @Route("/blog", name="cdo_site_admin_params_blog")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function blogAction(Request $request, $subdomain)
    {
        $params_path = $this->container->get('kernel')->getRootDir().'/../custom/'.$subdomain.'/params.json';
        
        $params_array = json_decode(file_get_contents($params_path), true);
        
        $fields_array = array(
            'blog_title' => $params_array['blog']['title'],
            'blog_placement' => $params_array['blog']['placement'],
        );
        $placement_array = array(
            '_content' => '1 bloc: Contenu',
            '_tree_content' => '2 blocs: Menu à gauche - Contenu à droite',
            '_content_tree' => '2 blocs: Contenu à gauche - Menu à droite',
        );
        
        $form = $this->createFormBuilder($fields_array)
                     ->add('blog_title', 'text', array(
                         'label' => 'Titre du blog',
                         'required' => false,
                     ))
                     ->add('blog_placement', 'choice', array(
                         'choices' => $placement_array,
                         'label' => 'Disposition',
                     ))
                     ->getForm();
        
        if ($request->isMethod('POST')) {
            $form->bind($request);

            $data = $form->getData();
            
            $params_array['blog']['title'] = $data['blog_title'];
            $params_array['blog']['placement'] = $data['blog_placement'];
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
