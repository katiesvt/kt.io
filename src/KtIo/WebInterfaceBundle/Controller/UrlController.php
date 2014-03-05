<?php

namespace KtIo\WebInterfaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use KtIo\WebInterfaceBundle\Entity\Url;

class UrlController extends Controller
{
    public function fetchAction($hash)
    {
        throw $this->createNotFoundException('Not yet implemented');
    }
    
    public function newAction(Request $request)
    {
        $url = new Url();

        $form = $this->createFormBuilder($url)
            ->add('target_url')
            ->getForm();

        return $this->render(
            'KtIoWebInterfaceBundle:Url:new.html.twig',
            array('form' => $form->createView())
        );
    }

    public function createAction($url)
    {
        throw $this->createNotFoundException('not yet implemented');
    }
}
