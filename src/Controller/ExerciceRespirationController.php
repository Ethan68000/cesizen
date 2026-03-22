<?php

namespace App\Controller;

use App\Entity\ExerciceRespiration;
use App\Form\ExerciceRespirationType;
use App\Repository\ExerciceRespirationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/exercice/respiration')]
final class ExerciceRespirationController extends AbstractController
{
    #[Route(name: 'app_exercice_respiration_index', methods: ['GET'])]
    public function index(ExerciceRespirationRepository $exerciceRespirationRepository, Request $request): Response
    {
        $predefinis = $exerciceRespirationRepository->findBy(['isPredefini' => true]);

        $mesExercices = [];
        if ($this->getUser()) {
            $mesExercices = $exerciceRespirationRepository->findBy([
                'user'        => $this->getUser(),
                'isPredefini' => false,
            ]);
        } else {
            $mesExercices = $request->getSession()->get('exercices_session', []);
        }

        return $this->render('exercice_respiration/index.html.twig', [
            'exercices_predefinis' => $predefinis,
            'mes_exercices'        => $mesExercices,
        ]);
    }

    #[Route('/new', name: 'app_exercice_respiration_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $exerciceRespiration = new ExerciceRespiration();
        $form = $this->createForm(ExerciceRespirationType::class, $exerciceRespiration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $exerciceRespiration->setIsPredefini(false);

            if ($this->getUser()) {
                $exerciceRespiration->setUser($this->getUser());
                $entityManager->persist($exerciceRespiration);
                $entityManager->flush();
                $this->addFlash('success', 'Exercice sauvegardé !');
            } else {
                $session = $request->getSession();
                $exercices = $session->get('exercices_session', []);
                $exercices[] = [
                    'id'              => uniqid(),
                    'nameSeries'      => $exerciceRespiration->getNameSeries(),
                    'timeInspiration' => $exerciceRespiration->getTimeInspiration(),
                    'timeApnea'       => $exerciceRespiration->getTimeApnea(),
                    'timeExpiration'  => $exerciceRespiration->getTimeExpiration(),
                ];
                $session->set('exercices_session', $exercices);
                $this->addFlash('warning', 'Exercice créé temporairement. Connectez-vous pour le sauvegarder !');
            }

            return $this->redirectToRoute('app_exercice_respiration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice_respiration/new.html.twig', [
            'exercice_respiration' => $exerciceRespiration,
            'form'                 => $form,
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
        if (!$this->isGranted('ROLE_ADMIN') && $exerciceRespiration->getUser() !== $this->getUser()) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cet exercice.');
            return $this->redirectToRoute('app_exercice_respiration_index');
        }

        $form = $this->createForm(ExerciceRespirationType::class, $exerciceRespiration);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_exercice_respiration_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('exercice_respiration/edit.html.twig', [
            'exercice_respiration' => $exerciceRespiration,
            'form'                 => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_exercice_respiration_delete', methods: ['POST'])]
    public function delete(Request $request, ExerciceRespiration $exerciceRespiration, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted('ROLE_ADMIN') && $exerciceRespiration->getUser() !== $this->getUser()) {
            $this->addFlash('danger', 'Vous n\'avez pas accès à cet exercice.');
            return $this->redirectToRoute('app_exercice_respiration_index');
        }

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

    #[Route('/export/json', name: 'app_exercice_respiration_export', methods: ['GET'])]
    #[IsGranted('ROLE_USER')]
    public function export(ExerciceRespirationRepository $repo): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $exercices = $repo->findAll();
        } else {
            $exercices = $repo->findBy([
                'user'        => $this->getUser(),
                'isPredefini' => false,
            ]);
        }

        $data = [];
        foreach ($exercices as $exercice) {
            $data[] = [
                'nameSeries'      => $exercice->getNameSeries(),
                'timeInspiration' => $exercice->getTimeInspiration(),
                'timeApnea'       => $exercice->getTimeApnea(),
                'timeExpiration'  => $exercice->getTimeExpiration(),
            ];
        }

        $response = new JsonResponse($data);
        $response->headers->set('Content-Disposition', 'attachment; filename="exercices.json"');
        return $response;
    }

    #[Route('/import/json', name: 'app_exercice_respiration_import', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function import(Request $request, EntityManagerInterface $em): Response
    {
        $uploadedFile = $request->files->get('json_file');

        if (!$uploadedFile || $uploadedFile->getClientOriginalExtension() !== 'json') {
            $this->addFlash('danger', 'Veuillez uploader un fichier JSON valide.');
            return $this->redirectToRoute('app_exercice_respiration_index');
        }

        $content = file_get_contents($uploadedFile->getPathname());
        $data = json_decode($content, true);

        if (!is_array($data)) {
            $this->addFlash('danger', 'Le fichier JSON est invalide.');
            return $this->redirectToRoute('app_exercice_respiration_index');
        }

        $count = 0;
        foreach ($data as $item) {
            if (!isset($item['nameSeries'], $item['timeInspiration'], $item['timeApnea'], $item['timeExpiration'])) {
                continue;
            }

            $exercice = new ExerciceRespiration();
            $exercice->setNameSeries($item['nameSeries']);
            $exercice->setTimeInspiration((int) $item['timeInspiration']);
            $exercice->setTimeApnea((int) $item['timeApnea']);
            $exercice->setTimeExpiration((int) $item['timeExpiration']);
            $exercice->setIsPredefini(false);
            $exercice->setUser($this->getUser());
            $em->persist($exercice);
            $count++;
        }

        $em->flush();
        $this->addFlash('success', $count . ' exercice(s) importé(s) avec succès !');
        return $this->redirectToRoute('app_exercice_respiration_index');
    }

    #[Route('/{id}/export', name: 'app_exercice_respiration_export_one', methods: ['GET'])]
    public function exportOne(ExerciceRespiration $exerciceRespiration): Response
    {
        $data = [[
            'nameSeries'      => $exerciceRespiration->getNameSeries(),
            'timeInspiration' => $exerciceRespiration->getTimeInspiration(),
            'timeApnea'       => $exerciceRespiration->getTimeApnea(),
            'timeExpiration'  => $exerciceRespiration->getTimeExpiration(),
        ]];

        $response = new JsonResponse($data);
        $response->headers->set('Content-Disposition', 'attachment; filename="exercice_' . $exerciceRespiration->getId() . '.json"');
        return $response;
    }

    #[Route('/session/launch/{sessionId}', name: 'app_exercice_respiration_launch_session', methods: ['GET'])]
    public function launchSession(Request $request, string $sessionId): Response
    {
        $exercices = $request->getSession()->get('exercices_session', []);
        $exercice = null;

        foreach ($exercices as $ex) {
            if ($ex['id'] === $sessionId) {
                $exercice = $ex;
                break;
            }
        }

        if (!$exercice) {
            $this->addFlash('danger', 'Exercice introuvable.');
            return $this->redirectToRoute('app_exercice_respiration_index');
        }

        return $this->render('exercice_respiration/launch_session.html.twig', [
            'exercice' => $exercice,
        ]);
    }
}