<?php

namespace App\Controller;

use App\Repository\ExerciceRespirationRepository;
use App\Repository\InformationsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(
        InformationsRepository $informationsRepository,
        ExerciceRespirationRepository $exerciceRespirationRepository
    ): Response {
        return $this->render('home/index.html.twig', [
            'informations' => $informationsRepository->findAll(),
            'exercices'    => $exerciceRespirationRepository->findBy([], null, 3),
        ]);
    }
}