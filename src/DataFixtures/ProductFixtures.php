<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Entity\User;
use App\Enum\OrderStatus;
use App\Enum\ProductStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProductFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $category1 = new Category();
        $category1->setName('Décoration');
        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('Mobilier');
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName('Électronique');
        $manager->persist($category3);

        // Création de l'utilisateur 1
        $user1 = new User();
        $user1->setEmail('Daniel.gaboucado@gmail.com');
        $user1->setFirstName('Daniel');
        $user1->setLastName('Gaboucado');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1,'password1'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('ouiza.kaddour487@hotmail.com');
        $user2->setFirstName('Ouiza');
        $user2->setLastName('KADDOUR');
        $user2->setRoles(['ROLE_ADMIN']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2,'password2'));
        $manager->persist($user2);

        // Création de l'utilisateur 3
        $user3 = new User();
        $user3->setEmail('richard.bureau@gmail.com');
        $user3->setFirstName('Richard');
        $user3->setLastName('Bureau');
        $user3->setRoles(['ROLE_ADMIN']);
        $user3->setPassword($this->passwordHasher->hashPassword($user3,'password3'));
        $manager->persist($user3);

        //création de l'utilisateur 4
        $user4 = new User();
        $user4->setEmail('christian.ilaneige@gmail.com');
        $user4->setFirstName('Christian');
        $user4->setLastName('Ilaneige');
        $user4->setRoles(['ROLE_ADMIN']);
        $user4->setPassword($this->passwordHasher->hashPassword($user4,'password4'));
        $manager->persist($user4);

        // Products and images
        $product1 = new Product();
        $product1->setName('Coussin Design');
        $product1->setPrice(29.99);
        $product1->setDescription('Un coussin design parfait pour votre salon.');
        $product1->setStock(50);
        $product1->setStatus(ProductStatus::DISPONIBLE);
        $product1->setCategory($category1);
        $manager->persist($product1);

        $image1 = new Image();
        $image1->setUrl('coussin.jpg');
        $image1->setProduct($product1);
        $manager->persist($image1);

        $product2 = new Product();
        $product2->setName('Vase en céramique');
        $product2->setPrice(49.99);
        $product2->setDescription('Un vase en céramique fait main pour décorer votre intérieur.');
        $product2->setStock(30);
        $product2->setStatus(ProductStatus::PRECOMMANDE);
        $product2->setCategory($category1);
        $manager->persist($product2);

        $image2 = new Image();
        $image2->setUrl('vase.jpg');
        $image2->setProduct($product2);
        $manager->persist($image2);

        $product3 = new Product();
        $product3->setName('Table en bois');
        $product3->setPrice(199.99);
        $product3->setDescription('Une belle table en bois massif.');
        $product3->setStock(20);
        $product3->setStatus(ProductStatus::DISPONIBLE);
        $product3->setCategory($category2);
        $manager->persist($product3);

        $image3 = new Image();
        $image3->setUrl('table_bois.jpg');
        $image3->setProduct($product3);
        $manager->persist($image3);

        $product4 = new Product();
        $product4->setName('Fauteuil confortable');
        $product4->setPrice(149.99);
        $product4->setDescription('Un fauteuil confortable pour se détendre.');
        $product4->setStock(15);
        $product4->setStatus(ProductStatus::DISPONIBLE);
        $product4->setCategory($category2);
        $manager->persist($product4);

        $image4 = new Image();
        $image4->setUrl('fauteuil.jpg');
        $image4->setProduct($product4);
        $manager->persist($image4);

        $product5 = new Product();
        $product5->setName('Lampe de bureau design');
        $product5->setPrice(59.99);
        $product5->setDescription('Une lampe de bureau moderne et élégante.');
        $product5->setStock(40);
        $product5->setStatus(ProductStatus::DISPONIBLE);
        $product5->setCategory($category3);
        $manager->persist($product5);

        $image5 = new Image();
        $image5->setUrl('lampe.jpg');
        $image5->setProduct($product5);
        $manager->persist($image5);

        $product6 = new Product();
        $product6->setName('Tableau moderne');
        $product6->setPrice(79.99);
        $product6->setDescription('Un tableau moderne pour votre intérieur.');
        $product6->setStock(10);
        $product6->setStatus(ProductStatus::DISPONIBLE);
        $product6->setCategory($category1);
        $manager->persist($product6);

        $image6 = new Image();
        $image6->setUrl('tableau.jpg');
        $image6->setProduct($product6);
        $manager->persist($image6);

        $product7 = new Product();
        $product7->setName('Étagère murale');
        $product7->setPrice(99.99);
        $product7->setDescription('Une étagère murale pratique et esthétique.');
        $product7->setStock(25);
        $product7->setStatus(ProductStatus::DISPONIBLE);
        $product7->setCategory($category2);
        $manager->persist($product7);

        $image7 = new Image();
        $image7->setUrl('etagere.jpg');
        $image7->setProduct($product7);
        $manager->persist($image7);

        // Création d'une adresse pour user1
        $address1 = new Address();
        $address1->setStreet('43 Rue du plaisir');
        $address1->setPostalCode('57000');
        $address1->setCity('Metz');
        $address1->setCountry('France');
        $address1->setOwner($user1); // Associe l'adresse à l'utilisateur 1
        $manager->persist($address1);

        // Création d'une adresse pour user2
        $address2 = new Address();
        $address2->setStreet('78 Rue de la voiture volée');
        $address2->setPostalCode('75000');
        $address2->setCity('Paris');
        $address2->setCountry('France');
        $address2->setOwner($user2); // Associe l'adresse à l'utilisateur 2
        $manager->persist($address2);

        // Création d'une adresse pour user2
        $address3 = new Address();
        $address3->setStreet('72 Rue du poisson braisé');
        $address3->setPostalCode('78523');
        $address3->setCity('Paris');
        $address3->setCountry('France');
        $address3->setOwner($user3);
        $manager->persist($address3);

        $order1 = new Order();
        $order1->setReference('ORD-54648');
        $order1->setCreatedAt(new \DateTime());
        $order1->setStatus(OrderStatus::EN_PREPARATION);
        $order1->setCustomer($user1);
        $manager->persist($order1);

        $order2 = new Order();
        $order2->setReference('ORD-7894');
        $order2->setCreatedAt(new \DateTime());
        $order2->setStatus(OrderStatus::ANNULEE);
        $order2->setCustomer($user2);
        $manager->persist($order2);

        $order3 = new Order();
        $order3->setReference('ORD-8974');
        $order3->setCreatedAt(new \DateTime());
        $order3->setStatus(OrderStatus::LIVREE);
        $order3->setCustomer($user3);
        $manager->persist($order3);

        $orderItem1 = new OrderItem();
        $orderItem1->setQuantity(2);
        $orderItem1->setProductPrice($product1->getPrice());
        $orderItem1->setPurchase($order1);
        $orderItem1->setProduct($product1);
        $manager->persist($orderItem1);

        $orderItem2 = new OrderItem();
        $orderItem2->setQuantity(1);
        $orderItem2->setProductPrice($product2->getPrice());
        $orderItem2->setPurchase($order1);
        $orderItem2->setProduct($product2);
        $manager->persist($orderItem2);

        $manager->flush();
    }
}
