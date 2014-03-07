<?php

namespace KtIo\WebInterfaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

use KtIo\WebInterfaceBundle\Entity\Url;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UrlController extends Controller
{
    /**
     * Fetches a given URL given its hash.
     * @param  string $hash An eight-character hash.
     * @throws NotFoundHttpException
     * @return RedirectResponse a redirect
     */
    public function fetchAction($hash)
    {
        $em = $this->getDoctrine()->getManager();
        $url = $em->getRepository('KtIoWebInterfaceBundle:Url')->findOneByShortUrl($hash);

        if (!$url)
            throw $this->createNotFoundException("Couldn't find that URL!");

        return $this->redirect($url->getTargetUrl());
    }

    /**
     * Shows a welcome page and a form for creating a short URL.
     * @param  Request $request
     * @return RedirectResponse|Response
     */
    public function newAction(Request $request)
    {
        $url = new Url();
        $url->setUser($this->get('kt_io_web_interface.user_service')->getUser());

        $form = $this->createFormBuilder($url)
            ->add('target_url')
            ->add('save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            // TODO move this elsewhere?
            $em = $this->getDoctrine()->getManager();
            $em->persist($url);
            $em->flush();

            // TODO create a URL service and use that instead
            $mqService = $this->get('kt_io_web_interface.message_queue_service');
            $mqService->sendProcessUrlRequest($url);

            return $this->redirect($this->generateUrl('url_show', array('id' => $url->getId())));
        }

        return $this->render(
            'KtIoWebInterfaceBundle:Url:new.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Shows an informational page upon creation of a new short URL.
     * @param  integer $id The created URL.
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $url = $em->getRepository('KtIoWebInterfaceBundle:Url')->find($id);

        if (!$url)
            throw $this->createNotFoundException();

        // TODO check we own this url
        return $this->render(
            'KtIoWebInterfaceBundle:Url:show.html.twig',
            array('url' => $url)
        );
    }

    /**
     * Deletes a given Url.
     * @param $id Id of the Url to delete.
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @todo Make sure the owner is the same as the URL!
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $url = $em->getRepository('KtIoWebInterfaceBundle:Url')->find($id);

        if (!$url)
            throw $this->createNotFoundException();

        $em->remove($url);
        $em->flush();

        return $this->redirect($this->generateUrl('url_new'));
    }

    /**
     * Shows all the URLs for the current user.
     * @param null $id
     * @return Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexForUserAction($id = null)
    {
        $user = null;

        if ($id === null)
        {
            $user = $this->get('kt_io_web_interface.user_service')->getUser();
        } else {
            // TODO this is disabled for now as we don't want to view other user's urls
            throw $this->createForbiddenException();
            //$user = $this->getDoctrine()->getManager()->getRepository('KtIoWebInterfaceBundle:User')->find($id);
        }

        if ($user === null)
            throw $this->createNotFoundException();

        return $this->render(
            'KtIoWebInterfaceBundle:Url:index_for_user.html.twig',
            array('urls' => $user->getUrls())
        );
    }

}
