<?php

namespace App\Form;

use App\Entity\Team;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Faction;

class TeamType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('faction', EntityType::class, [
                'class' => Faction::class,
                'choice_label' => 'name',
                'label' => 'Faction',
                'attr' => [
                    'data-team-faction' => true,
                ],
            ])
            ->add('playType', ChoiceType::class, [
                'label' => 'Type de jeu',
                'choices' => [
                    'Ligue' => 'league',
                    'Tournoi' => 'tournament',
                ],
            ])
            ->add('playCategory', ChoiceType::class, [
                'label' => 'Catégorie',
                'choices' => [
                    'Open' => 'open',
                    'Sevens' => 'sevens',
                ],
            ])
            ->add('rerolls', IntegerType::class, [
                'label' => 'Relances',
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('assistantCoaches', IntegerType::class, [
                'label' => 'Entraîneurs adjoints',
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('cheerleaders', IntegerType::class, [
                'label' => 'Pom-pom girls',
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('dedicatedFans', IntegerType::class, [
                'label' => 'Fans dévoués',
                'attr' => [
                    'min' => 0,
                ],
            ])
            ->add('apothecary', IntegerType::class, [
                'label' => 'Apothicaire',
                'attr' => [
                    'min' => 0,
                    'max' => 1,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Team::class,
            'faction_id' => null,
        ]);
    }
}