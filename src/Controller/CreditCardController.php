<?php

namespace App\Controller;

use App\Entity\CreditCard;
use App\Form\CreditCardType;
use App\Repository\CreditCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CreditCardController extends AbstractController
{
    #[Route('/credit-cards', name: 'credit_cards')]
    public function list(EntityManagerInterface $entityManager): Response
    {
        $creditCards = $entityManager->getRepository(CreditCard::class)->findAll();
        return $this->render('credit_card/list.html.twig', [
            'creditCards' => $creditCards,
        ]);
    }

    #[Route('/add-credit-card', name: 'add_credit_card')]
    public function add(): Response
    {
        return $this->render('credit_card/index.html.twig');
    }



}
