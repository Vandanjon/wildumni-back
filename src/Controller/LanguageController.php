<?php

namespace App\Controller;

use App\Repository\LanguageRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LanguageController extends AbstractController
{
    #[Route('/language', name: 'language', methods: ["GET"])]
    public function index(LanguageRepository $languageRepository): JsonResponse
    {

        $languages = $languageRepository->findAll();
        return $this->json(data: $languages, context: ["groups" => "getLanguages"]);
    }
}
