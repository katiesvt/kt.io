<?php

namespace KtIo\WebInterfaceBundle\Tests\Controller;

use KtIo\WebInterfaceBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KtIo\WebInterfaceBundle\Entity\Url;

class RestControllerTest extends WebTestCase
{
    private $entityManager;
    private $url;

    /**
     * Prepare the database for functional testing.
     */
    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        $this->entityManager = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $this->url = $this->createUrl();
    }

    /**
     * Shut down the database correctly.
     */
    public function tearDown()
    {
        $this->entityManager->remove($this->url);
        $this->entityManager->flush();

        parent::tearDown();
        $this->entityManager->close();
    }

    public function testPostUrlsAction()
    {
        $client = static::createClient();

        // we should ALWAYS get a 403 for now
        $client->request('POST', '/api/v1/urls');
        $this->assertTrue($client->getResponse()->isServerError());
    }

    /**
     * Fetches ALL the urls.
     */
    public function testGetUrlsAction()
    {
        $client = static::createClient();

        // we should get a 200 when there are urls...
        $client->request('GET', '/api/v1/urls');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->checkJson($client);

        // TODO test the format of the response
    }

    /**
     * Fetch a redirect from a short URL.
     */
    public function testGetUrlAction()
    {
        $client = static::createClient();

        // we should get a 200 when valid
        $client->request('GET', '/api/v1/urls/' . $this->url->getId());
        $this->assertTrue($client->getResponse()->isSuccessful());

        // when valid, we should expect json
        $this->checkJson($client);
        // TODO test the format of the response

        // we should be 404'd if not
        $client->request('GET', '/api/v1/urls/99999');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Deletes a given URL
     */
    public function testDeleteUrlAction()
    {
        $client = static::createClient();

        // successful delete
        $client->request('DELETE', '/api/v1/urls/' . $this->url->getId());
        $this->assertTrue($client->getResponse()->isSuccessful());

        // when url is not found
        $client->request('DELETE', '/api/v1/urls/99999');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Generate a test short URL for us.
     */
    private function createUrl($shortUrl="aaaaaaaa", $targetUrl="https://www.google.ca")
    {
        $url = new Url();
        $url->setShortUrl($shortUrl)->setTargetUrl($targetUrl);

        $this->entityManager->persist($url);
        $this->entityManager->flush();

        return $url;
    }

    /**
     * A simple check to make sure JSON is returned.
     *
     * @param Symfony\Bundle\FrameworkBundle\Client $client
     */
    private function checkJson($client)
    {
       $this->assertTrue(
           $client->getResponse()->headers->contains(
               'Content-Type',
               'application/json'
           )
       );
    }
}
