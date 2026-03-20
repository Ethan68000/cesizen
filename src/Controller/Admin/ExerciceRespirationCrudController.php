<?php

namespace App\Controller\Admin;

use App\Entity\ExerciceRespiration;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ExerciceRespirationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ExerciceRespiration::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nameSeries', 'Nom de la série'),
            IntegerField::new('timeInspiration', 'Inspiration (s)'),
            IntegerField::new('timeApnea', 'Apnée (s)'),
            IntegerField::new('timeExpiration', 'Expiration (s)'),
        ];
    }
}