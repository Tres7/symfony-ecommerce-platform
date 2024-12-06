<?php
namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Enum\OrderStatus;
use App\Repository\ProductRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart/add/{id}', name: 'cart_add')]
    public function add(Product $product,Request $request,SessionInterface $session,ProductRepository $productRepository):JsonResponse
    {
        /// take the cart from the session
        $cart = $session->get('cart', []);
        $id = $product->getId();

        if (!empty($cart[$id])) {
            if($cart[$id] < $product->getStock())
            {
                $cart[$id]++;
            } else
            {
                $this->addFlash('error', 'Le stock du produit ' . $product->getName() . ' est insuffisant.');
            }

        } else {
            if ($product->getStock() > 0) {
                $cart[$id] = 1;
            } else {
                $this->addFlash('error', 'Le stock du produit ' . $product->getName() . ' est insuffisant.');
            }
        }
        // update the cart in the session
        $session->set('cart', $cart);

        $cartDetails = $this->getCartDetails($session, $productRepository);

        $cartHtml = $this->renderView('cart/cart_content.html.twig', [
            'cartDetails' => $cartDetails['items'],
            'total' => $cartDetails['total'],
        ]);

        return $this->json([
            'totalItems' => $cartDetails['totalItems'],
            'totalPrice' => $cartDetails['total'],
            'cartHtml' => $cartHtml,
        ]);
    }


    private function getCartDetails(SessionInterface $session, ProductRepository $productRepository): array
    {
        $cart = $session->get('cart', []);
        $cartDetails = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $cartDetails[$productId] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->getPrice() * $quantity,
                ];
                $total += $product->getPrice() * $quantity;
            }
        }

        return [
            'items' => $cartDetails,
            'total' => $total,
            'totalItems' => array_sum($cart),
        ];
    }



    #[Route('/cart/remove/{id}', name: 'cart_remove')]
    public function remove(Product $product, SessionInterface $session, ProductRepository $productRepository): JsonResponse
    {
        // Récupérer le panier de la session
        $cart = $session->get('cart', []);

        // Supprimer l'article du panier
        $productId = $product->getId();
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        // Mettre à jour le panier dans la session
        $session->set('cart', $cart);

        // Récupérer les détails du panier pour la mise à jour
        $cartDetails = $this->getCartDetails($session, $productRepository);

        // Rendre une vue partielle pour mettre à jour le DOM dynamiquement
        $cartHtml = $this->renderView('cart/cart_content.html.twig', [
            'cartDetails' => $cartDetails['items'],
            'total' => $cartDetails['total'],
            'totalItems' => $cartDetails['totalItems'],
        ]);

        return $this->json([
            'success' => true,
            'cartHtml' => $cartHtml,
            'totalItems' => $cartDetails['totalItems'],
        ]);
    }


    #[Route('/cart', name: 'cart_show')]
    public function show(SessionInterface $session, ProductRepository $productRepository)
    {
        $cart = $session->get('cart', []);
        $cartDetails = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $cartDetails[$productId] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
                $total += $product->getPrice() * $quantity;
            }
        }
        return $this->render('cart/show.html.twig', [
            'cartDetails' => $cartDetails,
            'total'=> $total ?? 0
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * increase the quantity of a product in the cart
     **/
    #[Route('/cart/increase/{id}', name: 'cart_increase', methods: ['POST'])]
    public function increase(Product $product, SessionInterface $session, ProductRepository $productRepository): JsonResponse
    {
        $cart = $session->get('cart', []);
        $productId = $product->getId();

        if (array_key_exists($productId, $cart)) {
            if ($cart[$productId] < $product->getStock()) {
                $cart[$productId]++;
            } else {
                return $this->json([
                    'success' => false,
                    'message' => "Stock insuffisant pour ce produit.",
                ], 400);
            }
        } else {
            return $this->json([
                'success' => false,
                'message' => "Le produit n'existe pas dans le panier.",
            ], 400);
        }

        $session->set('cart', $cart);

        $cartDetails = $this->getCartDetails($session, $productRepository);

        return $this->json([
            'success' => true,
            'quantity' => $cart[$productId],               // Quantité mise à jour
            'subtotal' => $cartDetails['items'][$productId]['subtotal'], // Sous-total
            'cartTotal' => $cartDetails['total'],          // Total général du panier
            'totalItems' => $cartDetails['totalItems'],    // Nombre total d'articles
        ]);
    }



    /**
     * @param Product $product
     * @param Request $request
     * @return Response
     * decrease the quantity of a product in the cart
     */
    #[Route('/cart/decrease/{id}', name: 'cart_decrease', methods: ['POST'])]
    public function decrease(Product $product, SessionInterface $session, ProductRepository $productRepository): JsonResponse
    {
        $cart = $session->get('cart', []);
        $productId = $product->getId();

        if (isset($cart[$productId])) {
            if ($cart[$productId] > 1) {
                $cart[$productId]--;
            } else {
                unset($cart[$productId]);
            }
        }

        $session->set('cart', $cart);

        $cartDetails = $this->getCartDetails($session, $productRepository);

        return $this->json([
            'success' => true,
            'quantity' => $cart[$productId] ?? 0,
            'subtotal' => $cartDetails['items'][$productId]['subtotal'] ?? 0,
            'cartTotal' => $cartDetails['total'],
            'totalItems' => $cartDetails['totalItems'],
        ]);
    }



    #[Route('/cart/refresh', name: 'cart_refresh')]
    public function refresh(SessionInterface $session, ProductRepository $productRepository): Response
    {
        $cart = $session->get('cart', []);
        $cartDetails = [];

        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product) {
                $cartDetails[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                ];
            }
        }

        return $this->render('cart/cart_content.html.twig', [
            'cartDetails' => $cartDetails,
        ]);
    }

    #[Route('/checkout', name: 'checkout')]
    public function checkout(Request $request,SessionInterface $session,ProductRepository $productRepository,EntityManagerInterface $entityManager): RedirectResponse {
        if (!$this->getUser()) {
            $this->addFlash('error', 'Vous devez être connecté pour effectuer un achat.');
            return $this->redirectToRoute('login'); // Redirection vers la page de connexion
        }

        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('cart_show');
        }
        $order = new Order();
        $order->setCustomer($this->getUser());
        $orderCount = $entityManager->getRepository(Order::class)->count([]);
        $year = (new \DateTime())->format('Y');
        $reference = sprintf('ORD-%s-%04d', $year, $orderCount + 1);
        $order->setReference($reference);

//        $order->setReference(uniqid('ORDER_', true));
        $order->setCreatedAt(new \DateTime());
        $order->setStatus(OrderStatus::EN_PREPARATION);

        $cart = $session->get('cart', []);
        foreach ($cart as $productId => $quantity) {
            $product = $productRepository->find($productId);
            if ($product->getStock() < $quantity) {
                $this->addFlash('error', 'Le stock du produit ' . $product->getName() . ' est insuffisant.');
                return $this->redirectToRoute('cart_show');

            }

            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setProductPrice($product->getPrice());
            $orderItem->setPurchase($order);

            $entityManager->persist($orderItem);

            $product->setStock($product->getStock() - $quantity);
            $entityManager->persist($product);
        }

        $entityManager->persist($order);
        $entityManager->flush();

        $session->remove('cart');
        $this->addFlash('success', 'Votre commande a été passée avec succès.');
        return $this->redirectToRoute('home');
    }




}



?>