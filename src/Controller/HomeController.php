<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(ProductRepository $productRepository, SessionInterface $session): Response
    {
        $products = $productRepository->findAll();
        $cart = $session->get('cart', []);
        $totalItems = array_sum($cart);
        return $this->render('base.html.twig', [
            'products' => $products,
            'controller_name' => 'HomeController',
            'totalItems' => $totalItems
        ]);
    }

//    #[Route('/cart/number', name: 'cart_number')]
//    public function cartNumber(ProductRepository $productRepository, SessionInterface $session): Response
//    {
//        $products = $productRepository->findAll();
//        $cart = $session->get('cart', []);
//        $totalItems = array_sum($cart);
//        return $this->render('header.html.twig', [
//            'products' => $products,
//            'totalItems' => $totalItems
//        ]);
//
//    }
}
