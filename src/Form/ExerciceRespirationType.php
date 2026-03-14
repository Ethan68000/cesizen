<?php

namespace App\Form;

use App\Entity\ExerciceRespiration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExerciceRespirationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameSeries')
            ->add('timeInspiration')
            ->add('timeApnea')
            ->add('timeExpiration')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExerciceRespiration::class,
        ]);
    }
}
