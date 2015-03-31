<?php

namespace Cdo\MediaBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cdo\MediaBundle\Entity\Image;
use Cdo\MediaBundle\Form\Admin\Image\UploadType;
use Cdo\MediaBundle\Form\Admin\Image\UpdateType;

/**
 * @Route("/admin/image")
 */
class ImageController extends Controller
{
    /**
     * @Route("/", name="cdo_media_admin_image_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        $image_list = $em->getRepository('CdoMediaBundle:Image')
                         ->getAllOrderedAlt($account);
                
        return array(
            'image_list' => $image_list,
        );
    }

    /**
     * @Route("/upload", name="cdo_media_admin_image_upload")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function uploadAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        $image = new Image();
        $image->setAccount($account);
        
        $form = $this->createForm(new UploadType, $image);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                if ($image->getNavbarlogo()) {
                	$image_logo = $em->getRepository('CdoMediaBundle:Image')
                                     ->findNavbarlogo($account);
                	if ($image_logo) {
                	    $image_logo->setNavbarlogo(false);
                	}
                }
                
                $em->persist($image);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'L\'image « '.$image->getAlt().' » a été uploadée.');
                
                return $this->redirect($this->generateUrl('cdo_media_admin_image_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{id}", name="cdo_media_admin_image_update")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function updateAction(Image $image, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        if ($account != $image->getAccount())
        {
	        return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $form = $this->createForm(new UpdateType, $image);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                if ($image->getNavbarlogo()) {
                	$image_logo = $em->getRepository('CdoMediaBundle:Image')
                                     ->findNavbarlogoOld($image, $account);
                	if ($image_logo) {
                	    $image_logo->setNavbarlogo(false);
                	}
                }
                
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'L\'image « '.$image->getAlt().' » a été modifiée.');
                
                return $this->redirect($this->generateUrl('cdo_media_admin_image_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'image' => $image,
        );
    }

    /**
     * @Route("/remove/{id}", name="cdo_media_admin_image_remove")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function removeAction(Image $image, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        if ($account != $image->getAccount())
        {
	        return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $image_alt = $image->getAlt();
        
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->getRequest();
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            if ($form->isValid()) {
            	// In case of restoring softdeleted file
            	$image->setNavbarlogo(false);
                $em->flush();
                
                $em->remove($image);
                $em->flush();
                
                $this->get('session')->getFlashBag()->add('success', 'L\'image « '.$image_alt.' » a été supprimée.');
                
                return $this->redirect($this->generateUrl('cdo_media_admin_image_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'image' => $image,
        );
    }
}
