<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\ExerciceRespirationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[IsGranted('ROLE_USER')]
#[Route('/mon-espace')]
class UserSpaceController extends AbstractController
{
    #[Route('', name: 'app_user_space')]
    public function index(ExerciceRespirationRepository $repo): Response
    {
        $mesExercices = $repo->findBy([
            'user'        => $this->getUser(),
            'isPredefini' => false,
        ]);

        return $this->render('user_space/index.html.twig', [
            'mesExercices' => $mesExercices,
            'user'         => $this->getUser(),
        ]);
    }

    #[Route('/supprimer', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, EntityManagerInterface $em, TokenStorageInterface $tokenStorage): Response
    {
        if ($this->isCsrfTokenValid('delete_user', $request->request->get('_token'))) {
            $user = $this->getUser();
            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();
            $em->remove($user);
            $em->flush();
            $this->addFlash('success', 'Votre compte a été supprimé.');
        }

        return $this->redirectToRoute('app_home');
    }
}