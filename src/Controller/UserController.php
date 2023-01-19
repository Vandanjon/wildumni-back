<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user', methods: ["GET"])]
    public function index(UserRepository $userRepository, SerializerInterface $serializer): JsonResponse
    {
        $jsonUsers = $serializer->serialize($userRepository->findAll(), "json", ["groups" => "getUsers"]);

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route("/users/{id}", name: "getOne", methods: ["GET"])]
    public function show(User $user, SerializerInterface $serializer): JsonResponse
    {
        $jsonUser = $serializer->serialize($user, "json", ["groups" => "getUsers"]);

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route("/users", name: "create", methods: ["POST"])]
    public function create(Request $request, SerializerInterface $serializer, UserRepository $userRepository, UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $hasher): Response
    {
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        dd($user);

        $password = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);

        $userRepository->save($user, true);

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);


        $location = $urlGenerator->generate('getOne', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }


    #[Route("/users/{id}", name: "update", methods: ["PUT"])]
    public function update(Request $request, SerializerInterface $serializer, User $user, UserRepository $userRepository): JsonResponse
    {
        return $this->json("toto");
    }

    #[Route("/users/{id}", name: "delete", methods: ["DELETE"])]
    public function delete(User $user, UserRepository $userRepository): JsonResponse
    {
        $userRepository->remove($user, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
