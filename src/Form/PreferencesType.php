<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Document\Preferences;

class PreferencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('musique', CheckboxType::class, [
                'label'    => 'Musique autorisée pendant le trajet',
                'required' => false,
            ])
            ->add('climatisation', CheckboxType::class, [
                'label'    => 'Climatisation activée',
                'required' => false,
            ])
            ->add('animauxAcceptes', CheckboxType::class, [
                'label'    => 'Animaux acceptés à bord',
                'required' => false,
            ])
            ->add('autresPreferences', TextType::class, [
                'label'    => 'Autres préférences',
                'mapped'   => false,
                'required' => false,
                'attr'     => [
                    'placeholder' => 'Ex: Silence, Pas de téléphone, Arrêts fréquents...'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Preferences::class,
        ]);
    }
}