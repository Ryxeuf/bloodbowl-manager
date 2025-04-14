<?php

namespace App\Form;

use App\Entity\Player;
use App\Entity\Position;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class PlayerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', IntegerType::class, [
                'label' => 'Numéro',
                'attr' => [
                    'min' => 1,
                    'max' => 99,
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'Nom',
            ])
            ->add('position', EntityType::class, [
                'class' => Position::class,
                'choice_label' => 'name',
                'label' => 'Position',
                'attr' => [
                    'data-player-position' => true,
                ],
                'choice_attr' => function($position) {
                    return ['data-faction-id' => $position->getFaction()->getId()];
                },
                'query_builder' => function (EntityRepository $er) use ($options) {
                    if (isset($options['faction_id'])) {
                        return $er->createQueryBuilder('p')
                            ->where('p.faction = :faction')
                            ->setParameter('faction', $options['faction_id'])
                            ->orderBy('p.name', 'ASC');
                    }
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Player::class,
            'faction_id' => null,
        ]);
    }
} 