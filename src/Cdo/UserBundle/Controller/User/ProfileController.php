<?php

namespace Cdo\UserBundle\Controller\User;

use FOS\UserBundle\Controller\ProfileController as BaseController;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/admin/profile")
 */
class ProfileController extends BaseController
{
    /**
     * @Route("/show", name="cdo_user_user_profile_show")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function showAction()
    {
        $user = $this->getUser();
        $subdomain_route = $this->getRequest()->getSession()->get('subdomain');
        $subdomain_user = $user->getAccount()->getSubdomain();
        if ($subdomain_route != $subdomain_user) {
            throw new AccessDeniedException();
        }
        
    	return array(
            'user' => $user,
    	    'subdomain' => $subdomain_user,
    	);
    }

    /**
     * @Route("/edit", name="cdo_user_user_profile_edit")
     * @Template()
     * @Secure(roles="ROLE_ACCOUNT")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        if (!is_object($user) || !$user instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $subdomain_route = $this->getRequest()->getSession()->get('subdomain');
        $subdomain_user = $user->getAccount()->getSubdomain();
        if ($subdomain_route != $subdomain_user) {
            throw new AccessDeniedException();
        }

        /** @var $dispatcher \Symfony\Component\EventDispatcher\EventDispatcherInterface */
        $dispatcher = $this->get('event_dispatcher');

        $event = new GetResponseUserEvent($user, $request);
        $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_INITIALIZE, $event);

        if (null !== $event->getResponse()) {
            return $event->getResponse();
        }

//        /** @var $formFactory \FOS\UserBundle\Form\Factory\FactoryInterface */
//        $formFactory = $this->get('cdo_user.profile.form.factory');
//
//        $form = $formFactory->createForm();
//        $form->setData($user);
        $form = $this->container->get('form.factory')->create('cdo_user_profile', $user);

//        if ('POST' === $request->getMethod()) {
//            $form->bind($request);
//
//            if ($form->isValid()) {
//                /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
//                $userManager = $this->container->get('fos_user.user_manager');
//
//                $event = new FormEvent($form, $request);
//                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);
//
//                $userManager->updateUser($user);
//        
//                if (null === $response = $event->getResponse()) {
//                    $url = $this->container->get('router')->generate('fos_user_profile_show');
//                    $response = new RedirectResponse($url);
//                }
//
//                $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));
//
//                return $response;
//            }
//        }
//
//        return $this->container->get('templating')->renderResponse(
//            'FOSUserBundle:Profile:edit.html.twig',
//            array('form' => $form->createView())
//        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var $userManager \FOS\UserBundle\Model\UserManagerInterface */
            $userManager = $this->get('fos_user.user_manager');

            $event = new FormEvent($form, $request);
            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_SUCCESS, $event);

            $userManager->updateUser($user);

            if (null === $response = $event->getResponse()) {
                $url = $this->generateUrl('cdo_user_user_profile_show', array(
                    'subdomain' => $user->getAccount()->getSubdomain(),
                ));
                $response = new RedirectResponse($url);
            }

            $dispatcher->dispatch(FOSUserEvents::PROFILE_EDIT_COMPLETED, new FilterUserResponseEvent($user, $request, $response));

            return $response;
        }

        return array(
            'form' => $form->createView()
        );
    }
}
