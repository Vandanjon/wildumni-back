<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\ContactLink;
use App\Repository\UserRepository;
use App\Repository\AddressRepository;
use App\Repository\SessionRepository;
use App\Repository\LanguageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/users', name: 'app_user', methods: ["GET"])]
    public function index(
        UserRepository      $userRepository,
        SerializerInterface $serializer
    ): JsonResponse {
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
        Request                     $request,
        SerializerInterface         $serializer,
        UserRepository              $userRepository,
        UrlGeneratorInterface       $urlGenerator,
        UserPasswordHasherInterface $hasher,
        LanguageRepository          $languageRepository,
        AddressRepository           $addressRepository,
        SessionRepository           $sessionRepository
    ): JsonResponse {

        /** @var User $user */
        $user = $serializer->deserialize($request->getContent(), User::class, 'json');

        $password = $hasher->hashPassword($user, $user->getPassword());
        $user->setPassword($password);

        $latitude = $user->getAddress()->getLatitude();
        $longitude = $user->getAddress()->getLongitude();
        $address = $addressRepository->findOneBy(["latitude" => $latitude, "longitude" => $longitude]);

        if (!$address) {
            $address = new Address();
            $address
                ->setCountry($user->getAddress()->getCountry())
                ->setRegion($user->getAddress()->getRegion())
                ->setCity($user->getAddress()->getCity())
                ->setPostcode($user->getAddress()->getPostcode())
                ->setStreet($user->getAddress()->getStreet())
                ->setStreetNumber($user->getAddress()->getStreetNumber())
                ->setLatitude($latitude)
                ->setLongitude($longitude);
            $addressRepository->save($address, true);
        }

        $user->setAddress($address);

        $user->initCollections();
        $sessions = json_decode($request->getContent())->session;

        foreach ($sessions as $sessionObj) {
            $session = $sessionRepository->findOneBy(["location" => $sessionObj->location]);
            if ($session) {
                $user->addSession($session);
            }
        }

        $languages = json_decode($request->getContent())->language;

        foreach ($languages as $languageObj) {
            $language = $languageRepository->findOneBy(['name' => $languageObj->name]);
            if ($language) {
                $user->addLanguage($language);
            }
        }

        $contactLinks = json_decode($request->getContent())->contactLink;

        $contactArray = new ContactLink();
        $contactArray->setGithub($contactLinks[0]->github);
        $contactArray->setGitlab($contactLinks[0]->gitlab);
        $contactArray->setBitbucket($contactLinks[0]->bitbucket);
        $contactArray->setTwitter($contactLinks[0]->twitter);
        $contactArray->setLinkedin($contactLinks[0]->linkedin);

        $user->addContactLink($contactArray);

        $userRepository->save($user, true);

        $jsonUser = $serializer->serialize($user, 'json', ['groups' => 'getUsers']);

        $location = $urlGenerator->generate('getOne', ['id' => $user->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }


    #[Route("/users/{id}", name: "update", methods: ["PUT"])]
    public function update(
        Request                     $request,
        SerializerInterface         $serializer,
        User                        $user,
        UserRepository              $userRepository,
        UrlGeneratorInterface       $urlGenerator,
        UserPasswordHasherInterface $hasher,
        LanguageRepository          $languageRepository,
        AddressRepository           $addressRepository,
        SessionRepository           $sessionRepository

    ): JsonResponse {

        /** @var User $user */
        $userToUpdate = $serializer->deserialize($request->getContent(), User::class, "json", [AbstractNormalizer::OBJECT_TO_POPULATE => $user]);

        $password = $hasher->hashPassword($userToUpdate, $userToUpdate->getPassword());
        $userToUpdate->setPassword($password);

        $latitude = $userToUpdate->getAddress()->getLatitude();
        $longitude = $userToUpdate->getAddress()->getLongitude();
        $address = $addressRepository->findOneBy(["latitude" => $latitude, "longitude" => $longitude]);

        if (!$address) {
            $address = new Address();
            $address
                ->setCountry($userToUpdate->getAddress()->getCountry())
                ->setRegion($userToUpdate->getAddress()->getRegion())
                ->setCity($userToUpdate->getAddress()->getCity())
                ->setPostcode($userToUpdate->getAddress()->getPostcode())
                ->setStreet($userToUpdate->getAddress()->getStreet())
                ->setStreetNumber($userToUpdate->getAddress()->getStreetNumber())
                ->setLatitude($latitude)
                ->setLongitude($longitude);
            $addressRepository->save($address, true);
        }

        $userToUpdate->setAddress($address);

        $userToUpdate->initCollections();
        $sessions = json_decode($request->getContent())->session;

        foreach ($sessions as $sessionObj) {
            $session = $sessionRepository->findOneBy(["location" => $sessionObj->location]);
            if ($session) {
                $userToUpdate->addSession($session);
            }
        }

        $languages = json_decode($request->getContent())->language;

        foreach ($languages as $languageObj) {
            $language = $languageRepository->findOneBy(['name' => $languageObj->name]);
            if ($language) {
                $userToUpdate->addLanguage($language);
            }
        }

        $contactLinks = json_decode($request->getContent())->contactLink;

        $contactArray = new ContactLink();
        $contactArray->setGithub($contactLinks[0]->github);
        $contactArray->setGitlab($contactLinks[0]->gitlab);
        $contactArray->setBitbucket($contactLinks[0]->bitbucket);
        $contactArray->setTwitter($contactLinks[0]->twitter);
        $contactArray->setLinkedin($contactLinks[0]->linkedin);

        $userToUpdate->addContactLink($contactArray);

        $userRepository->save($userToUpdate, true);

        $jsonUser = $serializer->serialize($userToUpdate, 'json', ['groups' => 'getUsers']);

        $location = $urlGenerator->generate('getOne', ['id' => $userToUpdate->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonUser, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    #[Route("/users/{id}", name: "delete", methods: ["DELETE"])]
    public function delete(
        User            $user,
        UserRepository  $userRepository
    ): JsonResponse {

        $userRepository->remove($user, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
