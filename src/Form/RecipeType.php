<?php

namespace App\Form;

use App\Entity\Diet;
use App\Entity\Recipe;
use App\Entity\Allergen;
use App\Repository\AllergenRepository;
use App\Repository\DietRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Titre',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Description',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('preparation_time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => '1'
                ],
                'label' => 'Temps de préparation (en min)',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive()
                ]
            ])
            ->add('timeout', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Temps de repos (en min)',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero()
                ]
            ])
            ->add('cooking_time', IntegerType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Temps de cuisson (en min)',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero()
                ]
            ])
            ->add('ingredient', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Ingrédients (séparés par ",")',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('steps', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Etapes (ex Etape 1 : ... ; Etape 2 : ... ; )',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ]
            ])
            ->add('isPremium', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check-input'
                ],
                'required' => false,
                'label' => 'Recette premium',
                'label_attr' => [
                    'class' => 'form-check-label'
                ],
                'constraints' => [
                    new Assert\NotNull()
                ]
            ])
            ->add('diets', EntityType::class, [
                'class' => Diet::class,
                'label' => 'Régimes',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'query_builder' => function (DietRepository $dietRepository) {
                    return $dietRepository->createQueryBuilder('diet')
                        ->orderBy('diet.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('allergens', EntityType::class, [
                'class' => Allergen::class,
                'label' => 'Allergènes',
                'label_attr' => [
                    'class' => 'form-label mt-3'
                ],
                'query_builder' => function (AllergenRepository $allergenRepository) {
                    return $allergenRepository->createQueryBuilder('allergen')
                        ->orderBy('allergen.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-outline-primary mt-3'
                ],
                'label' => 'Enregistrer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recipe::class,
        ]);
    }
}
