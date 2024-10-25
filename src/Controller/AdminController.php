<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Form\ProductType;
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

        return $this->render('admin/index.html.twig', [
            'users' => $users,
            'controller_name' => 'AdminController',
        ]);
    }
     /**
      *affichage de la liste des produits
      */
    #[Route('/admin/products', name: 'admin_products')]
    public function listProducts(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $entityManager->getRepository(Product::class)->createQueryBuilder('p')->getQuery();
        $products = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

        return $this->render('admin/products.html.twig', [
            'products' => $products,
        ]);
    }
    /**
     * affichage de la liste des commandes
     * @param EntityManagerInterface $entityManager
     */
    #[Route('/admin/orders', name: 'admin_orders')]
    public function listOrders(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $entityManager->getRepository(Order::class)->createQueryBuilder('o')->getQuery();
        $orders = $paginator->paginate($query, $request->query->getInt('page', 1), 10);

        return $this->render('admin/orders.html.twig', [
            'orders' => $orders,
        ]);
    }


    /**
     * creation d'un nouveau produit
     */
    #[Route('/admin/product/new', name: 'product_new')]
    public function createProduct(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
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
    public function editProduct(Product $product, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
    public function deleteProduct(Product $product, EntityManagerInterface $entityManager): Response
    {
        //vérifier si le produit est présent dans des commandes
        if (!$product->getOrderItems()->isEmpty()) {
            $this->addFlash('error', 'Impossible de supprimer ce produit car il est déjà présent dans des commandes.');
            return $this->redirectToRoute('admin_products');
        }

        //sinon on peut supprimer le produit
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'Produit supprimé avec succès.');

        return $this->redirectToRoute('admin_products');
    }
}
