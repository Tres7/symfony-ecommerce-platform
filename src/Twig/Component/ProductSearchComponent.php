<?php
namespace App\Twig\Component;

use App\Repository\ProductRepository;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('product_search')]
class ProductSearchComponent
{
    use DefaultActionTrait;

    #[LiveProp(writable: true)]
    public ?string $query = ''; // Stocke la recherche utilisateur

    public function __construct(private ProductRepository $productRepository)
    {
    }

    public function getProducts(): array
    {
        // Si aucune recherche, retourne tous les produits
        if (empty($this->query)) {
            return $this->productRepository->findAll();
        }

        // Filtrer les produits par la requête
        return $this->productRepository->searchByName($this->query);
    }

    public function __invoke(): array
    {
        // Rend la variable 'products' disponible dans le template
        return ['products' => $this->getProducts()];
    }
}

?>