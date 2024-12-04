<?php

namespace App\Form;

use App\Entity\CreditCard;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Regex;

class CreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'form.credit_card.number.label',
                'required' => true,
                'attr' => [
                    'maxlength' => 16,
                    'placeholder' => 'form.credit_card.number.placeholder',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'form.credit_card.number.required'),
                    new Assert\Length(
                        min: 16,
                        max: 16,
                        minMessage: 'form.credit_card.number.length',
                        maxMessage: 'form.credit_card.number.length',
                    ),
                ],
            ])
            ->add('expirationDate', DateType::class, [
                'data' => new \DateTime(),
                'label' => 'form.credit_card.expiration_date.label',
                'constraints' => [
                    new Assert\NotBlank(message: 'form.credit_card.expiration_date.required'),
                    new Assert\Type(type: \DateTimeInterface::class, message: 'form.credit_card.expiration_date.invalid'),
                ],
            ])
            ->add('cvv', TextType::class, [
                'label' => 'form.credit_card.cvv.label',
                'required' => true,
                'attr' => [
                    'maxlength' => 3,
                    'placeholder' => 'form.credit_card.cvv.placeholder',
                ],
                'constraints' => [
                    new Assert\NotBlank(message: 'form.credit_card.cvv.required'),
                    new Assert\Length(
                        min: 3,
                        max: 3,
                        exactMessage: 'form.credit_card.cvv.length',
                    ),
                    new Regex([
                        'pattern' => '/^\d{3}$/',
                        'message' => 'form.credit_card.cvv.invalid',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreditCard::class,
        ]);
    }
}
