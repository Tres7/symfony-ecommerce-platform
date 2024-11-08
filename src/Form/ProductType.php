<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use App\Enum\ProductStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ])
            ->add('description')
            ->add('stock', NumberType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ])

            ->add('status', ChoiceType::class, [
                'choices' => ProductStatus::cases(),
                'choice_label' => function (?ProductStatus $status) {
                    return $status->name ?? '';
                },
                'choice_value' => function (?ProductStatus $status) {
                    return $status->value ?? '';
                },
                'label' => 'Statut du produit',
            ])

            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
            ])

            ->add('images', FileType::class, [
                'label' => 'Images du produit',
//                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Veuillez uploader une image valide',
                    ]),
                ],
//                'attr' => [
//                    'accept' => 'image/*',
//                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
