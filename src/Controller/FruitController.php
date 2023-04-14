<?php

namespace App\Controller;

use App\Repository\FruitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FruitController extends AbstractController
{
    #[Route('/fruits', name: 'fruits')]
    public function index(Request $request, FruitRepository $fruitRepository): JsonResponse
    {
        $name = $request->query->get('name');
        $families = $request->query->get('families');
        $page = (int)$request->query->get('page', 1);
        $limit = (int)$request->query->get('limit', 10);
        $fruits = $fruitRepository->search($name, $families, $page, $limit);
        $count = $fruitRepository->countSearchResults($name, $families);

        $responseData = [
            'page' => $page,
            'limit' => $limit,
            'count' => $count,
            'fruits' => $fruits,
        ];

        return $this->json($responseData);
    }

    #[Route('/fruits/families', name: 'fruit_families')]
    public function getFamilies(FruitRepository $fruitRepository): JsonResponse
    {
        $families = $fruitRepository->getFamilies();

        return $this->json($families);
    }
}
