<?php

namespace KtIo\WebInterfaceBundle\Controller;

use FOS\RestBundle\View\RouteRedirectView,
    FOS\RestBundle\View\View,
    FOS\RestBundle\Controller\Annotations\QueryParam,
    FOS\RestBundle\Request\ParamFetcherInterface;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Bundle\FrameworkBundle\Controller\Controller;

use KtIo\WebInterfaceBundle\Entity\Url;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class RestController extends Controller
{

    /**
     * @ApiDoc(
     *  description="Creates a new URL",
     *  input="KtIo\WebInterfaceBundle\Entity\Url"
     * )
     * @todo
     */
    public function postUrlsAction()
    {
        throw new \Exception("not implemented");
    }

    /**
     * Gets all of the URLs. This is very dangerous and slow and should NEVER go out to production!
     * @ApiDoc(
     *  description="Returns a collection of URLs",
     *  resource=true,
     *  output="KtIo\WebInterfaceBundle\Entity\Url"
     * )
     * @todo Don't make this API publicly accessible
     */
    public function getUrlsAction()
    {
        $urls = $this->getDoctrine()->getManager()->getRepository('KtIoWebInterfaceBundle:Url')->findAll();

        $view = new View($urls);
        $view->setTemplate('KtIoWebInterfaceBundle:Rest:getUrls.html.twig');
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Gets information about a specific URL entry.
     * @param integer $id ID number of the URL to fetch.
     * @ApiDoc(
     *  description="Returns a given URL",
     *  output="KtIo\WebInterfaceBundle\Entity\Url",
     *  statusCodes={
     *    200 = "Returned when successful",
     *    404 = "Returned when the URL with the given ID was not found."
     *  }
     * )
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return
     */
    public function getUrlAction($id)
    {
        $url = $this->getDoctrine()->getRepository('KtIoWebInterfaceBundle:Url')->find($id);

        if (!$url)
            throw $this->createNotFoundException();

        $view = new View($url);
        $view->setTemplate('KtIoWebInterfaceBundle:Rest:getUrl.html.twig');

        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /**
     * Deletes a given URL. Normally we would do security here to make sure users can only delete their own
     * URL, so don't put this on production!!
     *
     * @param integer $id ID number of the URL to delete.
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @return bool
     * @ApiDoc(
     *  description="Deletes a given URL"
     * )
     */
    public function deleteUrlAction($id)
    {
        $url = $this->getDoctrine()->getRepository('KtIoWebInterfaceBundle:Url')->find($id);

        if (!$url)
            throw $this->createNotFoundException();

        $em = $this->getDoctrine()->getManager();
        $em->remove($url);
        $em->flush();

        // TODO there's probably a better way to do this
        $view = new View();
        return $this->get('fos_rest.view_handler')->handle($view);
    }
}