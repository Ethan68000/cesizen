<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Informations;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
            ])
            ->add('slug', TextType::class, [
                'label' => 'Slug',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
            ])
            ->add('categories', EntityType::class, [
                'class'         => Category::class,
                'choice_label'  => 'name',
                'multiple'      => true,
                'expanded'      => true,
                'label'         => 'Catégories',
                'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('c')->orderBy('c.name', 'ASC'),
                'required'      => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Informations::class,
        ]);
    }
}