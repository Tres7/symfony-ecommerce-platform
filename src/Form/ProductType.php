<?php

namespace App\Form;

use App\Entity\Product;
use App\Enum\ProductStatus;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
