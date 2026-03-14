<?php

namespace App\Controller;

use App\Entity\ExerciceRespiration;
use App\Form\ExerciceRespirationType;
use App\Repository\ExerciceRespirationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/exercice/respiration')]
final class ExerciceRespirationController extends AbstractController
{
    #[Route(name: 'app_exercice_respiration_index', methods: ['GET'])]
    public function index(ExerciceRespirationRepository $exerciceRespirationRepository): Response
    {
        return $this->render('exercice_respiration/index.html.twig', [
            'exercice_respirations' => $exerciceRespirationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_exercice_respiration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exerciceRespiration = new ExerciceRespiration();
        $form = $this->createForm(ExerciceRespirationType::class, $exerciceRespiration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($exerciceRespiration);
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice_respiration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice_respiration/new.html.twig', [
            'exercice_respiration' => $exerciceRespiration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exercice_respiration_show', methods: ['GET'])]
    public function show(ExerciceRespiration $exerciceRespiration): Response
    {
        return $this->render('exercice_respiration/show.html.twig', [
            'exercice_respiration' => $exerciceRespiration,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_exercice_respiration_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ExerciceRespiration $exerciceRespiration, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ExerciceRespirationType::class, $exerciceRespiration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_exercice_respiration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice_respiration/edit.html.twig', [
            'exercice_respiration' => $exerciceRespiration,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exercice_respiration_delete', methods: ['POST'])]
    public function delete(Request $request, ExerciceRespiration $exerciceRespiration, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exerciceRespiration->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($exerciceRespiration);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_exercice_respiration_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/launch', name: 'app_exercice_respiration_launch', methods: ['GET'])]
    public function launch(ExerciceRespiration $exerciceRespiration): Response
    {
        return $this->render('exercice_respiration/launch.html.twig', [
            'exercice_respiration' => $exerciceRespiration,
        ]);
    }
}
