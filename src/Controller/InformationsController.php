<?php

namespace App\Controller;

use App\Entity\Informations;
use App\Form\InformationsType;
use App\Repository\CategoryRepository;
use App\Repository\InformationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/informations')]
final class InformationsController extends AbstractController
{
    #[Route(name: 'app_informations_index', methods: ['GET'])]
    public function index(
        Request $request,
        InformationsRepository $informationsRepository,
        CategoryRepository $categoryRepository
    ): Response {
        $selectedCategoryId = $request->query->getInt('category', 0);
        $selectedCategory   = null;

        if ($selectedCategoryId > 0) {
            $selectedCategory = $categoryRepository->find($selectedCategoryId);
        }

        $informations = $selectedCategory
            ? $informationsRepository->findByCategory($selectedCategory)
            : $informationsRepository->findAll();

        return $this->render('informations/index.html.twig', [
            'informations'       => $informations,
            'categories'         => $categoryRepository->findBy([], ['name' => 'ASC']),
            'selectedCategoryId' => $selectedCategoryId,
        ]);
    }

    #[Route('/new', name: 'app_informations_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $information = new Informations();
        $form = $this->createForm(InformationsType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $information->setCreationDate(new \DateTime());
            $information->setAdmin($this->getUser());
            $entityManager->persist($information);
            $entityManager->flush();

            return $this->redirectToRoute('app_informations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('informations/new.html.twig', [
            'information' => $information,
            'form'        => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_informations_show', methods: ['GET'])]
    public function show(Informations $information): Response
    {
        return $this->render('informations/show.html.twig', [
            'information' => $information,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_informations_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Informations $information, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InformationsType::class, $information);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_informations_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('informations/edit.html.twig', [
            'information' => $information,
            'form'        => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_informations_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Informations $information, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$information->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($information);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_informations_index', [], Response::HTTP_SEE_OTHER);
    }
}