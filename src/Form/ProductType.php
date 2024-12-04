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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'form.product.name.label',
                'constraints' => [
                    new Assert\NotBlank(message: 'form.product.name.required'),
                ],
            ])
            ->add('price', NumberType::class, [
                'label' => 'form.product.price.label',
                'constraints' => [
                    new Assert\NotBlank(message: 'form.product.price.required'),
                    new Assert\Positive(message: 'form.product.price.positive'),
                ],
            ])
            ->add('description', TextType::class, [
                'label' => 'form.product.description.label',
            ])
            ->add('stock', NumberType::class, [
                'label' => 'form.product.stock.label',
                'constraints' => [
                    new Assert\NotBlank(message: 'form.product.stock.required'),
                    new Assert\Positive(message: 'form.product.stock.positive'),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'form.product.status.label',
                'choices' => ProductStatus::cases(),
                'choice_label' => function (?ProductStatus $status) {
                    return $status->name ?? '';
                },
                'choice_value' => function (?ProductStatus $status) {
                    return $status->value ?? '';
                },
            ])
            ->add('category', EntityType::class, [
                'label' => 'form.product.category.label',
                'class' => Category::class,
                'choice_label' => 'name',
                'autocomplete' => true,
            ])
            ->add('images', FileType::class, [
                'label' => 'form.product.images.label',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'form.product.images.mime_type',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
