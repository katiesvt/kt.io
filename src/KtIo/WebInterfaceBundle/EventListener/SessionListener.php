<?php
namespace KtIo\WebInterfaceBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use KtIo\WebInterfaceBundle\Services\UserService;

class SessionListener
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    /**
     * Create a user for the client if a token is not present in the session.
     * 
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $this->userService->createOrUpdateUser();
    }
}
