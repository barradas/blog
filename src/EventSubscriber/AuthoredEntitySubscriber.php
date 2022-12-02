<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class AuthoredEntitySubscriber implements EventSubscriberInterface
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['getAuthenticatedUser', EventPriorities::PRE_WRITE]
        ];
    }


    public function getAuthenticatedUser(ViewEvent $event)
    {
        $method = $event->getRequest()->getMethod();

        if ($event->getRequest()->attributes->all()['_api_resource_class'] !== 'App\Entity\BlogPost' || Request::METHOD_POST !== $method) {
            return;
        }

        $entity = $event->getControllerResult();

        /** @var UserInterface $author */
        $author = $this->tokenStorage->getToken()->getUser();

        $entity->setAuthor($author);
    }
}
