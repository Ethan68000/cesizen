<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Informations;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

/**
 * @extends AbstractCrudController<Informations>
 */

class InformationsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Informations::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre'),
            TextField::new('slug', 'Slug'),
            TextareaField::new('description', 'Description'),
            AssociationField::new('categories', 'Catégories')
                ->setFormTypeOptions(['by_reference' => false]),
            AssociationField::new('admin', 'Auteur (admin)')
                ->hideOnForm(),
            DateTimeField::new('creationDate', 'Date de création')
                ->hideOnForm(),
        ];
    }

    public function persistEntity(EntityManagerInterface $entityManager, mixed $entityInstance): void
    {
        if ($entityInstance instanceof Informations) {
            $admin = $this->getUser();
            if (!$admin instanceof User) {
                throw $this->createAccessDeniedException();
            }

            $entityInstance->setCreationDate(new \DateTime());
            $entityInstance->setAdmin($admin);
        }
        parent::persistEntity($entityManager, $entityInstance);
    }
}