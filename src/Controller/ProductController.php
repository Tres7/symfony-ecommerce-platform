<?php
// src/Controller/ProductController.php
namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    #[Route('/products/autocomplete', name: 'product_autocomplete')]
    public function autocomplete(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $query = $request->query->get('query', '');

        // Recherche des produits par nom
        $products = $productRepository->searchByName($query);

        // Retourne les suggestions sous forme de JSON
        return $this->json([
            'items' => array_map(fn($product) => [
                'id' => $product->getId(),
                'text' => $product->getName(),
            ], $products),
        ]);
    }

}


?>