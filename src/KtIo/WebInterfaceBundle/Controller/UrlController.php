<?php

namespace KtIo\WebInterfaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use KtIo\WebInterfaceBundle\Entity\Url;

class UrlController extends Controller
{
    /**
     * Fetches a given URL given its hash/
     * @param  string $hash An eight-character hash.
     */
    public function fetchAction($hash)
    {
        throw $this->createNotFoundException('Not yet implemented');
    }
    
    /**
     * Shows a welcome page and a form for creating a short URL.
     * @param  Request $request
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

            return $this->redirect($this->generateUrl('url_show', array('id' =>$url->getId())));
        }

        return $this->render(
            'KtIoWebInterfaceBundle:Url:new.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * Shows an informational page upon creation of a new short URL.
     * @param  integer    $id The created URL.
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $url = $em->getRepository('KtIoWebInterfaceBundle:Url')->find($id);

        // TODO check we own this url
        return $this->render(
            'KtIoWebInterfaceBundle:Url:show.html.twig',
            array('url' => $url)
        );
    }

}
