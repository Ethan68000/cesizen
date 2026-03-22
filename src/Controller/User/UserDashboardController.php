<?php

namespace App\Controller\User;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
#[AdminDashboard(routePath: '/dashboard', routeName: 'user_dashboard')]
class UserDashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mon espace CESIZen');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Mon espace', 'fa fa-home');
        yield MenuItem::section('Mon compte');
        yield MenuItem::linkToRoute('Mon profil', 'fa fa-user', 'user_dashboard_user_profile_index');
        yield MenuItem::section('Mes exercices');
        yield MenuItem::linkToRoute('Mes exercices', 'fa fa-wind', 'user_dashboard_user_exercice_index');
        yield MenuItem::section('');
        yield MenuItem::linkToRoute('Retour au site', 'fa fa-arrow-left', 'app_home');
    }
}