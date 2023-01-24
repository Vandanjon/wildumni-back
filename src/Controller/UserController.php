<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\User;
use App\Repository\LanguageRepository;
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
    public function create(
        Request $request,
        SerializerInterface $serializer,
        UserRepository $userRepository,
        UrlGeneratorInterface $urlGenerator,
        UserPasswordHasherInterface $hasher,
        LanguageRepository $languageRepository
    ): JsonResponse {

        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $password = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);

        $userRepository->save($user, true);

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);


        $location = $urlGenerator->generate('getOne', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);

        // $user = $serializer->deserialize($request->getContent(), User::class, 'json');
        // $data = json_decode($request->getContent(), true);



        // $languages = $data['language'] ?? null;
        // foreach ($languages as $languageName) {
        //     $language = $languageRepository->findOneBy(["name" => $languageName]);
        //     $user->addLanguage($language);
        // }

        // $objLanguages = new Language();

        // $objLanguages = array_map(function ($language) {
        //     return ['name' => $language];
        // }, $languages);




        // $user->addLanguage(array_map(fn ($language) => new Language($language), $languages));

        // $user->addLanguage($objLanguages);
        // return new JsonResponse($thisuser, Response::HTTP_OK, [], true);
        // return $this->json(data: $user, context: ["groups" => "getUsers"]);
        // $password = $hasher->hashPassword($user, $user->getPassword());
        // $user->setPassword($password);

        // $userRepository->save($user, true);

        // $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);


        // $location = $urlGenerator->generate('getOne', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        // return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
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
