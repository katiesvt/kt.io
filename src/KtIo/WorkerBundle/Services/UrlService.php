<?php

namespace KtIo\WorkerBundle\Services;

use Doctrine\ORM\EntityManager;

// TODO move this to a base bundle
use KtIo\WebInterfaceBundle\Entity\Url;
use KtIo\WebInterfaceBundle\Entity\UrlRepository;

class UrlService
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Generates a short URL for a given Url entity.
     * @param  Url    $url The URL entity to work on.
     */
    public function createShortUrl(Url $url)
    {
        // TODO make this more useful
        $url->setShortUrl(hash('crc32', $url->getTargetUrl()));
        $this->entityManager->flush();
    }

    /**
     * An admittedly ugly function to get a Url from a string that may require trimming.
     * @param  string $string
     * @return Url
     */
    public function findByIdString($string)
    {
        $repository = $this->entityManager->getRepository('KtIoWebInterfaceBundle:Url');
        return $repository->find(intval($string));
    }
}
