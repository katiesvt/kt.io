<?php

namespace KtIo\WebInterfaceBundle\Tests\Controller;

use KtIo\WebInterfaceBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use KtIo\WebInterfaceBundle\Entity\Url;

class UrlControllerTest extends WebTestCase
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


    /**
     * Fetch a redirect from a short URL.
     */
    public function testFetchAction()
    {
        $client = static::createClient();

        // we should be redirected if valid
        $crawler = $client->request('GET', '/aaaaaaaa');
        $this->assertTrue($client->getResponse()->isRedirection());

        // we should be 404'd if not
        $client->request('GET', '/bbbbbbbb');
        $this->assertTrue($client->getResponse()->isNotFound());
    }

    /**
     * Home page. Also allows users to submit a form.
     */
    public function testNewAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        // TODO finish this test
    }

    /**
     * Shows information about a given URL.
     */
    public function testShowAction()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/show/' . $this->url->getId());

        // see if 1. a 200 status code and 2. somewhere the short url is displayed
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertRegExp("/{$this->url->getShortUrl()}/", $client->getResponse()->getContent());

        // 404 if the url doesn't exist
        $client->request('GET', '/show/99999');
        $this->assertTrue($client->getResponse()->isNotFound());

        // TODO 403 if someone else owns the URL
    }

    /**
     * Deletes a URL given an id.
     */
    public function testDeleteAction()
    {
        $url = $this->createUrl();
        $client = static::createClient();
        $client->request('GET', '/delete/' . $this->url->getId());

        // 302 on successful delete
        $this->assertTrue($client->getResponse()->isRedirection());
        // url is deleted
        $client->request('GET', '/show/' . $this->url->getId());
        $this->assertTrue($client->getResponse()->isNotFound());

        // 404 if it doesn't exist
        $client->request('GET', '/delete/99999');
        $this->assertTrue($client->getResponse()->isNotFound());

    }

    public function testIndexForUserAction()
    {
        $client = static::createClient();

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
}
