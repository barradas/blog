<?php

namespace App\EventSubscriber;

use ApiPlatform\Core\EventListener\EventPriorities;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class UserConfimationSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{

    private $userRepository;
    private $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;

    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW => ['confirmUser', EventPriorities::POST_VALIDATE],
        ];
    }

    public function confirmUser($event)
    {

        $request = $event->getRequest();
        $route = $request->get('_route');

        if ('api_user_confirmations_post_collection' !== $route) {
            return;
        }

        $username = $event->getControllerResult()->username;

        $user = $this->userRepository->findOneBy(['username' => $username]);

        if ($user) {
            $event->setResponse(new JsonResponse(null, Response::HTTP_OK));
        }
    }
}
