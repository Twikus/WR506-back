<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class RegistrationController extends AbstractController
{
    private $passwordHasher;
    private $jwtTokenManager;
    private $doctrine;

    public function __construct(UserPasswordHasherInterface $passwordHasher, JWTTokenManagerInterface $jwtTokenManager, ManagerRegistry $doctrine)
    {
        $this->passwordHasher = $passwordHasher;
        $this->jwtTokenManager = $jwtTokenManager;
        $this->doctrine = $doctrine;
    }

    #[Route('/register', name: 'registration', methods: ['POST'])]
    public function register(Request $request): JsonResponse
    {
        // Récupérez les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Créez un nouvel utilisateur
        $user = new User();
        $user->setEmail($data['email']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);

        // Encodez le mot de passe (vous pouvez également ajouter d'autres logiques ici)
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        // Assignez le rôle (vous pouvez également avoir ROLE_ADMIN si nécessaire)
        $user->setRoles([$data['role']]);

        // Persistez l'utilisateur en utilisant ManagerRegistry
        $entityManager = $this->doctrine->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // Générez le token JWT
        $token = $this->jwtTokenManager->create($user);

        // Renvoyez le token dans la réponse JSON
        return new JsonResponse(['token' => $token, 'message' => 'Inscription réussie'], JsonResponse::HTTP_OK);
    }
}
