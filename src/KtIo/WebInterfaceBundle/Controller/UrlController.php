<?php

namespace KtIo\WebInterfaceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UrlController extends Controller
{
    public function fetchAction($hash)
    {
        throw $this->createNotFoundException('Not yet implemented');
    }
    
    public function createAction($url)
    {
        throw $this->createNotFoundException('not yet implemented');
    }
}
