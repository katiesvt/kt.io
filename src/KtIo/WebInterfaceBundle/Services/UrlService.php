<?php

namespace KtIo\WebInterfaceBundle\Services;

use KtIo\WebInterfaceBundle\Entity\Url;
use Doctrine\ORM\EntityManager;

class UrlService
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var MessageQueueService
     */
    private $mqService;

    public function __construct(EntityManager $entityManager, MessageQueueService $mqService)
    {
        $this->entityManager = $entityManager;
        $this->mqService = $mqService;
    }
    
}
