<?php

namespace KtIo\WebInterfaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render(
            'KtIoWebInterfaceBundle:Default:index.html.twig',
            array()
        );
    }
}
