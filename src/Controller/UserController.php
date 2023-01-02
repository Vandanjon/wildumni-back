<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route("/api/users", name: "getAll", methods: ["GET"])]
    public function index(UserRepository $userRepository, SerializerInterface $serial): JsonResponse
    {
        $jsonUsers = $serial->serialize($userRepository->findAll(), "json", ["groups" => "getUsers"]);

        return new JsonResponse($jsonUsers, Response::HTTP_OK, [], true);
    }

    #[Route("/api/users/{id}", name: "getOne", methods: ["GET"])]
    public function show(User $user, SerializerInterface $serial): JsonResponse
    {
        $jsonUser = $serial->serialize($user, "json", ["groups" => "getUsers"]);

        return new JsonResponse($jsonUser, Response::HTTP_OK, [], true);
    }

    #[Route("/api/users", name: "create", methods: ["POST"])]
    public function new(Request $request, SerializerInterface $serializer, UrlGeneratorInterface $urlGenerator, UserRepository $userRepository): Response 
    {
        $user = $serializer->deserialize($request->getContent(), User::class, "json");

        $userRepository->save($user, true);
    
        $jsonuser = $serializer->serialize($user, "json", ["groups" => "getUsers"]);
        $location = $urlGenerator->generate("users_getOne", ["id" => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonuser, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route("/api/users/{id}", name: "update", methods: ["PUT"])]
    public function update(Request $request, SerializerInterface $serializer, User $currentUser, EntityManagerInterface $em): JsonResponse
    {
        $updatedUser = $serializer->deserialize($request->getContent(), User::class, "json", [AbstractNormalizer::OBJECT_TO_POPULATE => $currentUser]);

        $em->persist($updatedUser);
        $em->flush();

        return new JsonResponse(null, JsonResponse::HTTP_NO_CONTENT);
    }

    #[Route("/api/users/{id}", name: "delete", methods: ["DELETE"])]
    public function delete(User $user, UserRepository $userRepository): JsonResponse
    {
        $userRepository->remove($user, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}