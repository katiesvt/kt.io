<?php

namespace KtIo\WorkerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('KtIoWorkerBundle:Default:index.html.twig', array('name' => $name));
    }
}
