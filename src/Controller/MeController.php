<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MeController extends AbstractController
{
    #[Route("/api/me", name:"get_current_user", methods:["GET"])]
    public function getCurrentUser(TokenStorageInterface $tokenStorage): JsonResponse
    {
         /** @var User $user */
         $user = $tokenStorage->getToken()->getUser();

         if (!$user instanceof User) {
             return new JsonResponse(['error' => 'Unauthenticated'], 401);
         }
 
         $userData = [
             'id' => $user->getId(),
             'email' => $user->getEmail(),
             'firstname' => $user->getFirstname(),
             'lastname' => $user->getLastname(),
             'roles' => $user->getRoles(),
         ];
         
         return new JsonResponse($userData);
    }
}
