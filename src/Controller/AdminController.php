<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/users', name: 'admin_users')]
    #[IsGranted('ROLE_ADMIN')]
    public function listUsers(EntityManagerInterface $entityManager, Request $request, PaginatorInterface $paginator): Response
    {
        $query = $entityManager->getRepository(User::class)->createQueryBuilder('u')->getQuery();
        $users = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('error', 'Vous n\'avez pas les droits pour accéder à cette page.');
            return $this->redirectToRoute('home');
        }


        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'controller_name' => 'AdminController',
        ]);
    }
     /**
      *affichage de la liste des produits
      */
    #[Route('/admin/products', name: 'admin_products')]
    #[IsGranted('ROLE_ADMIN')]
    public function listProducts(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $entityManager->getRepository(Product::class)->createQueryBuilder('p')->getQuery();
        $products = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

        return $this->render('product/products.html.twig', [
            'products' => $products,
        ]);
    }
    /**
     * affichage de la liste des commandes
     * @param EntityManagerInterface $entityManager
     */
    #[Route('/admin/orders', name: 'admin_orders')]
    #[IsGranted('ROLE_ADMIN')]
    public function listOrders(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $entityManager->getRepository(Order::class)->createQueryBuilder('o')->getQuery();
        $orders = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

        return $this->render('order/orders.html.twig', [
            'orders' => $orders,
        ]);
    }


    /**
     * creation d'un nouveau produit
     */
    #[Route('/admin/product/new', name: 'product_new')]
    #[IsGranted('ROLE_ADMIN')]
    public function createProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the product initially to assign an ID if needed
            $entityManager->persist($product);
            $entityManager->flush();

            $image = $form->get('images')->getData();
            if ($image) {
                $newFilename = uniqid('', true) . '.' . $image->guessExtension();

                // Déplace le fichier dans le répertoire d'upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $newFilename
                );

                $image = new Image();
                $image->setUrl($newFilename);
                $image->setProduct($product);
                $entityManager->persist($image);
            }

            // Final flush after all images are set
            $entityManager->flush();

            $this->addFlash('success', 'Produit ajouté avec succès.');

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('product/product_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * modification d'un produit
     */
    #[Route('/admin/product/edit/{id}', name: 'product_edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function editProduct(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            if ($images) {
                foreach ($images as $imageFile) {
                    $newFilename = uniqid('', true) . '.' . $imageFile->guessExtension();
                    $imageFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                    $image = new Image();
                    $image->setUrl($newFilename);
                    $image->setProduct($product);
                    $entityManager->persist($image);
                }
            }
            $entityManager->flush();

            $this->addFlash('success', 'Produit modifié avec succès.');

            return $this->redirectToRoute('admin_products');
        }

        return $this->render('product/product_form.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }




    /**
     * suppression d'un produit
     */
    #[Route('/admin/product/delete/{id}', name: 'product_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deleteProduct(Product $product, EntityManagerInterface $entityManager): Response
    {
        //vérifier si le produit est présent dans des commandes
        if (!$product->getOrderItems()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer ce produit car il est déjà présent dans des commandes.');
            return $this->redirectToRoute('admin_products');
        }

        foreach ($product->getImages() as $image) {
            unlink($this->getParameter('images_directory').'/'.$image->getUrl());
            $entityManager->remove($image);
        }

        //sinon on peut supprimer le produit
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Produit supprimé avec succès.');

        return $this->redirectToRoute('admin_products');
    }

    // Dans AdminController par exemple
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    #[IsGranted('ROLE_ADMIN')]
    public function dashboard(ProductRepository $productRepository, OrderRepository $orderRepository): Response
    {
        $productsByCategory = $productRepository->countProductsByCategory();
        $latestOrders = $orderRepository->findLatestOrders();
        $productAvailability = $productRepository->countProductByStatus();
        $monthlySales = $orderRepository->salesByMonth();

        return $this->render('admin/dashboard.html.twig', [
            'productsByCategory' => $productsByCategory,
            'latestOrders' => $latestOrders,
            'productAvailability' => $productAvailability,
            'monthlySales' => $monthlySales,
        ]);
    }
}
