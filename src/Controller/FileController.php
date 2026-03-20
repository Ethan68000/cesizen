<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Informations;
use App\Repository\FileRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/file')]
class FileController extends AbstractController
{
    #[Route('/upload/{id}', name: 'app_file_upload', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function upload(Request $request, Informations $informations, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $uploadedFile = $request->files->get('file');

            if ($uploadedFile) {
                $allowedMimeTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/gif',
                    'image/webp',
                    'application/pdf',
                    'video/mp4',
                    'video/mpeg',
                    'video/quicktime',
                    'video/webm',
                ];

                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'mp4', 'mpeg', 'mov', 'webm'];
                $extension = strtolower($uploadedFile->getClientOriginalExtension());

                if (!in_array($extension, $allowedExtensions)) {
                    $this->addFlash('danger', 'Type de fichier non autorisé.');
                    return $this->redirectToRoute('app_file_upload', ['id' => $informations->getId()]);
                }

                $fileName = uniqid() . '.' . $extension;
                $uploadedFile->move($this->getParameter('uploads_directory'), $fileName);

                $file = new File();
                $file->setType($uploadedFile->getClientMimeType());
                $file->setPath($fileName);
                $file->setInformations($informations);

                $em->persist($file);
                $em->flush();

                $this->addFlash('success', 'Fichier uploadé avec succès !');
            }

            return $this->redirectToRoute('app_informations_show', ['id' => $informations->getId()]);
        }

        return $this->render('file/upload.html.twig', [
            'informations' => $informations,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_file_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, File $file, EntityManagerInterface $em): Response
    {
        $informationsId = $file->getInformations()->getId();

        if ($this->isCsrfTokenValid('delete' . $file->getId(), $request->request->get('_token'))) {
            $filePath = $this->getParameter('uploads_directory') . '/' . $file->getPath();
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $em->remove($file);
            $em->flush();
            $this->addFlash('success', 'Fichier supprimé.');
        }

        return $this->redirectToRoute('app_informations_show', ['id' => $informationsId]);
    }
}