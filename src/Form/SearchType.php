<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints as Assert;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('adresse_depart')
            ->add('adresse_arrivee')
            ->add('Date', null, [
                'widget' => 'single_text',
                'constraints' => [
                    new Assert\GreaterThanOrEqual('today')
                ]
            ])
            ->add('nb_personnes', null,[
                'constraints' => [
                    new Assert\GreaterThanOrEqual([
                        'value' => 0,
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 5,
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
