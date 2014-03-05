<?php

namespace KtIo\WebInterfaceBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Session\Session;

use KtIo\WebInterfaceBundle\Entity\User;
use KtIo\WebInterfaceBundle\Entity\UserRepository;

class UserService
{
    /**
     * @var Doctrine
     */
    private $entityManager;

    /**
     * @var Session
     */
    private $session;

    /**
     * @var User
     */
    private $user;
    
    public function __construct(EntityManager $entityManager, Session $session)
    {
        $this->entityManager = $entityManager;
        $this->session = $session;
    }

    /**
     * Creates or updates the user as required by the state of the client's session.
     * @return UserService
     */
    public function createOrUpdateUser()
    {
        if ($this->getSession()->has('token')) {
            $this->updateUser();
        } else {
            $this->createUser();
        }
    }

    /**
     * Returns the current User.
     * @return User
     */
    public function getUser()
    {
        if ($this->user === null) {
            $session = $this->getSession();
            if (!$session->has('user_id')) throw new \Exception("Tried to UserService->getUser() without a session!");

            $this->user = $this->entityManager
                ->getRepository('KtIoWebInterfaceBundle:User')
                ->find($session->get('user_id'));
            // TODO use token for security
        }

        return $this->user;
    }

    /**
     * Creates a User for the client and persists it in the database.
     * @return UserService
     */
    private function createUser()
    {
        $user = new User();
        $user->createToken()->updateLastVisit();
        
        $em = $this->entityManager;
        $em->persist($user);
        $em->flush();

        $this->updateSession($user);

        return $this;
    }

    /**
     * Updates the last visit of the current user and persists it to the db.
     * @return UserService
     */
    private function updateUser()
    {
        $em = $this->entityManager;
        $em->persist($this->getUser()->updateLastVisit());
        $em->flush();

        return $this;
    }

    /**
     * Writes the given user info to the session.
     * @param  User $user
     * @return UserService
     */
    private function updateSession($user)
    {
        $session = $this->getSession();
        $session->set('token', $user->getToken());
        $session->set('user_id', $user->getId());

        $session->save();

        return $this;
    }

    /**
     * Gets the current Session object.
     * @return Session
     */
    private function getSession()
    {
        return $this->session;
    }
}
