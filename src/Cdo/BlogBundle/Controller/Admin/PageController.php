<?php

namespace Cdo\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cdo\BlogBundle\Entity\Page;
use Cdo\BlogBundle\Form\Admin\Page\CreateType;
use Cdo\BlogBundle\Form\Admin\Page\UpdateType;
use Cdo\BlogBundle\Event\PageEvents;
use Cdo\BlogBundle\Event\PagePostEvent;
use Cdo\BlogBundle\Event\PageRemoveEvent;

/**
 * @Route("/admin/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_admin_page_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        $page_collection = $this->container->get('cdo_blog.twig.page_extension')->pageListByLevel($subdomain);
        
        return array(
            'page_collection' => $page_collection,
        );
    }

    /**
     * @Route("/create", name="cdo_blog_admin_page_create")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function createAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');
        $cdo_blog_page_level_max = $this->container->getParameter('cdo_blog_page_level_max');
        
        $account = $this->getUser()->getAccount();
        
        $page_count = $em->getRepository('CdoBlogBundle:Page')
                         ->countAll($account);
        
        $page = new Page;
        $page->setAccount($account);
        $page->setRank($page_count);
        
        $form = $this->createForm(new CreateType($account->getId(), $cdo_blog_page_level_max), $page);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                if ($page_parent = $page->getParent()) {
                	if (!$page_parent->getDisplay() AND $page->getDisplay()) {
        	            throw new \Exception('Une nouvelle page ne peut être affiliée qu\'à une page publiée.');
                	}
                }
                
                $em->persist($page);
                
                $event = new PagePostEvent($securityContext, $page, $em);
                $this->get('event_dispatcher')->dispatch(PageEvents::onPagePost, $event);
                $event->setLevel();
                $event->updateRanks();
                $event->checkSlug();
                
                $this->container->get('cdo_site.twig.menu_extension')->encode($subdomain);
                
                $this->get('session')->getFlashBag()->add('success', 'La page « '.$page->getTitle().' » a été créée.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_page_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/update/{page}", name="cdo_blog_admin_page_update")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function updateAction(Page $page, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $page_rep = $em->getRepository('CdoBlogBundle:Page');
        $securityContext = $this->get('security.context');
        $cdo_blog_page_level_max = $this->container->getParameter('cdo_blog_page_level_max');
        
        $account = $this->getUser()->getAccount();
        if ($account->getId() != $page->getAccount()->getId()) {
            return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $page_count = $page_rep->countAll($account);
        
        $rank_array = array();
        $i = $page->getChildren()->isEmpty()
            ? 0
            : 1;
        while ($i < $page_count)
        {
        	$rank_array[] = $i;
        	$i++;
        }
        
    	$form = $this->createForm(new UpdateType($account->getId(), $page->getId(), $rank_array, $cdo_blog_page_level_max), $page);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                if ($page->getRank() == 0) {
                	$page_homepage = $page_rep->getByHomepage($account);
                	$page_homepage->setHomepage(false);
                	$page->setHomepage(true);
                }
                
                $event = new PagePostEvent($securityContext, $page, $em);
                $this->get('event_dispatcher')->dispatch(PageEvents::onPagePost, $event);
                $event->setLevel();
                $event->updateRanks();
                $event->checkSlug();
                
                if (!$page->getDisplay()) {
                    foreach ($page->getChildren() as $page_item) {
                        $page_item->setDisplay(false);
                    }
                    $em->flush();
                }
                $this->container->get('cdo_site.twig.menu_extension')->encode($account->getSubdomain());
                
                $this->get('session')->getFlashBag()->add('success', 'La page « '.$page->getTitle().' » a été mise à jour.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_page_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'page' => $page,
        );
    }

    /**
     * @Route("/remove/{page}", name="cdo_blog_admin_page_remove")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function removeAction(Page $page, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $securityContext = $this->get('security.context');
        
        $account = $this->getUser()->getAccount();
        if ($account->getId() != $page->getAccount()->getId()) {
	        return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $page_title = $page->getTitle();
        
        if (!$page->getChildren()->isEmpty())
        {
        	throw new \Exception('Vous ne pouvez pas supprimer cette page sans avoir supprimé auparavant les autres pages qui lui sont affiliées.');
        } elseif ($page->getHomepage()) {
        	throw new \Exception('Vous ne pouvez pas supprimer la page d\'accueil.');
        }
        
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->getRequest();
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            if ($form->isValid()) {
                $em->remove($page);
                $em->flush();
                
                $event = new PageRemoveEvent($securityContext, $em);
                $this->get('event_dispatcher')->dispatch(PageEvents::onPageRemove, $event);
                $event->updateRanks();
                
                $this->container->get('cdo_site.twig.menu_extension')->encode($account->getSubdomain());
                
                $this->get('session')->getFlashBag()->add('success', 'La page « '.$page_title.' » a été supprimée.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_page_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'page' => $page,
        );
    }
}
