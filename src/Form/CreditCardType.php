<?php

namespace App\Form;

use App\Entity\CreditCard;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreditCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Numéro de carte',
                'required' => true,

            ])
            ->add('expirationDate', DateType::class)
            ->add('cvv', TextType::class, [
                'label' => 'CVV',
                'required' => true,

            ])
//            ->add('user', EntityType::class, [
//                'class' => User::class,
//             'choice_label' => 'email',
//                'autocomplete' => true,
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreditCard::class,
        ]);
    }
}
