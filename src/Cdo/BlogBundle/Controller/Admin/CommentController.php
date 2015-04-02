<?php

namespace Cdo\BlogBundle\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Cdo\BlogBundle\Entity\Comment;
use Cdo\BlogBundle\Form\Admin\Comment\CommentType;
use Cdo\BlogBundle\Event\CommentEvents;
use Cdo\BlogBundle\Event\CommentPostEvent;

/**
 * @Route("/admin/comment")
 */
class CommentController extends Controller
{
    /**
     * @Route("/", name="cdo_blog_admin_comment_index")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function indexAction($subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $account = $this->getUser()->getAccount();
        
        $comment_collection = $em->getRepository('CdoBlogBundle:Comment')
                                 ->getAll($account);
        
        return array(
            'comment_collection' => $comment_collection,
        );
    }

    /**
     * @Route("/update/{comment}", name="cdo_blog_admin_comment_update")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function updateAction(Comment $comment, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        $cdo_blog_comment_status = $this->container->getParameter('cdo_blog_comment_status');
        
        $user = $this->getUser();
        $account = $user->getAccount();
        if ($account->getId() != $comment->getAccount()->getId()) {
            return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $form = $this->createForm(new CommentType($cdo_blog_comment_status), $comment);
        
        $request = $this->get('request');
        
        if($request->getMethod() == 'POST')
        {
            $form->bind($request);
            
            if($form->isValid())
            {
                $em->flush();
                
                $event = new CommentPostEvent($comment, $em);
                $this->get('event_dispatcher')->dispatch(CommentEvents::onCommentPost, $event);
                $event->updateNumberComments();
                
                $this->get('session')->getFlashBag()->add('success', 'Le commentaire a été mis à jour.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_comment_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }

        return array(
            'form' => $form->createView(),
            'comment' => $comment,
        );
    }

    /**
     * @Route("/remove/{comment}", name="cdo_blog_admin_comment_remove")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function removeAction(Comment $comment, $subdomain)
    {
        $em = $this->getDoctrine()->getManager();
        
        $account = $this->getUser()->getAccount();
        if ($account->getId() != $comment->getAccount()->getId()) {
	        return $this->redirect($this->generateUrl('ptm_site_visitor_error_accessdenied'));
        }
        
        $form = $this->createFormBuilder()->getForm();
        
        $request = $this->getRequest();
        
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            
            if ($form->isValid()) {
                $em->remove($comment);
                $em->flush();
                
                $event = new CommentPostEvent($comment, $em);
                $this->get('event_dispatcher')->dispatch(CommentEvents::onCommentPost, $event);
                $event->updateNumberComments();
                
                $this->get('session')->getFlashBag()->add('success', 'Le commentaire a été supprimé.');
                
                return $this->redirect($this->generateUrl('cdo_blog_admin_comment_index', array(
                    'subdomain' => $subdomain,
                )));
            }
        }
        
        return array(
            'form' => $form->createView(),
            'comment' => $comment,
        );
    }
}
