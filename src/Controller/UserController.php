<?php
namespace App\Controller;


use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{

     #[Route('/user/dashboard', name:'client_dashboard')]
    public function index(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findAll();
        $user = $this->getUser();
        $clientOrders = $orderRepository->findBy(['customer' => $user], ['createdAt' => 'DESC']);
        return $this->render('user/client_dashboard.html.twig', ['orders' => $orders, 'clientOrders' => $clientOrders,]);

    }

//    #[Route('/client/order-history', name: 'order_history')]
//    public function orderHistory(OrderRepository $orderRepository): Response
//    {
//        $user = $this->getUser();
//        $clientOrders = $orderRepository->findBy(['customer' => $user], ['createdAt' => 'DESC']);
//
//        return $this->render('user/order_history.html.twig', [
//            'clientOrders' => $clientOrders,
//        ]);
//    }

}

?>