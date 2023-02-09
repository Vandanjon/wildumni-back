<?php

namespace App\Controller;

use App\Repository\SessionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SessionController extends AbstractController
{
    #[Route("/api/sessions", name: "session", methods: ["GET"])]
    public function index(
        SessionRepository $sessionRepository,
        SerializerInterface $serializer
    ): JsonResponse {

        $sessions = $serializer->serialize($sessionRepository->findAll(), "json", ["groups" => "getSessions"]);

        return new JsonResponse($sessions, Response::HTTP_OK, [], true);
    }
}
