<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque')
            ->add('model')
            ->add('immatriculation')
            ->add('energie')
            ->add('energie', ChoiceType::class, [
                'choices'  => [
                    'Voitures Électriques' => 'Électriques',
                    'Voitures Thermiques (Essence)' => 'Essence',
                    'Voitures Thermiques (Diesel)' => 'Diesel',
                    'Voitures HEV et PHEV (Hybride)' => 'Hybride',
                    'E85 (Bioéthanol)' => 'E85 (Bioéthanol)',
                    'Gaz Naturel (GNV) et GPL (Gaz de Pétrole Liquéfié)' => 'Gaz Naturel (GNV) et GPL (Gaz de Pétrole Liquéfié)',
                    'Hydrogène (Voitures à Pile à Combustible - FCEV)' => 'Hydrogène (Voitures à Pile à Combustible - FCEV)'
                ],
                'expanded' => false,
                'data' => 'Voitures Électriques'
                ])
            ->add('couleur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
            'csrf_protection' => true
        ]);
    }
}
