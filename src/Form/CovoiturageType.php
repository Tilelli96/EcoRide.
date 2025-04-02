<?php

namespace App\Form;

use App\Entity\Covoiturage;
use App\Entity\User;
use App\Entity\Voiture;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints as Assert;

class CovoiturageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $voitures = $options['voitures']; 
        $builder
            ->add('date_depart', null, [
                'widget' => 'single_text',
                'attr' => ['class' => 'js-datepicker'],
                'constraints' => [
                    new Assert\GreaterThanOrEqual('today')
                ]
                
            ])
            ->add('heure_depart')
            ->add('lieu_depart')
            ->add('date_arrivee', null, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\GreaterThanOrEqual('today')
                ]
            ])
            ->add('heure_arrivee')
            ->add('lieu_arrivee')
            ->add('nb_places')
            ->add('prix_personne')
            ->add('voiture', EntityType::class, [
                'class' => Voiture::class,
                'choices' => $voitures,
                'choice_label' => 'model',
                'label' => 'Voiture'
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Covoiturage::class,
            'voitures' => null
        ]);
    }
}

