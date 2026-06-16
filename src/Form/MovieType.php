<?php

namespace App\Form;

use App\Entity\Movie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
// Définit les champs du formulaire d'ajout et de modification d'un film
// Liste des genres disponibles pour éviter les valeurs incorrectes
class MovieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('réalisateur')
            ->add('année')
            ->add('description')
            ->add('genres', ChoiceType::class, [
                'choices' => [
                    'Action' => 'Action',
                    'Science-Fiction' => 'Science-Fiction',
                    'Comédie' => 'Comédie',
                    'Horreur' => 'Horreur',
                    'Drame' => 'Drame',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movie::class,
        ]);
    }
}
