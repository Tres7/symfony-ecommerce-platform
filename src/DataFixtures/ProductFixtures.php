<?php

namespace App\DataFixtures;

use App\Entity\Address;
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
        // Création de l'utilisateur 1
        $user1 = new User();
        $user1->setEmail('Daniel.gaboucado@gmail.com');
        $user1->setFirstName('Daniel');
        $user1->setLastName('Gaboucado');
        $user1->setRoles(['ROLE_USER']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1,'password1'));
        $manager->persist($user1);

        // Création de l'utilisateur 2
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

        // Création de deux produits
        $product1 = new Product();
        $product1->setName('Coussin Design');
        $product1->setPrice(29.99);
        $product1->setDescription('Un coussin design parfait pour votre salon.');
        $product1->setStock(50);
        $product1->setStatus(ProductStatus::EN_RUPTURE);
        $manager->persist($product1);

        $product2 = new Product();
        $product2->setName('Vase en céramique');
        $product2->setPrice(49.99);
        $product2->setDescription('Un vase en céramique fait main pour décorer votre intérieur.');
        $product2->setStock(30);
        $product2->setStatus(ProductStatus::PRECOMMANDE);
        $manager->persist($product2);

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
        $address3->setOwner($user3); // Associe l'adresse à l'utilisateur 2
        $manager->persist($address3);

        // Création d'une commande pour user1
        $order1 = new Order();
        $order1->setReference('ORD-54648');
        $order1->setCreatedAt(new \DateTime());
        $order1->setStatus(OrderStatus::EN_PREPARATION);
        $order1->setCustomer($user1); // Associe la commande à l'utilisateur 1
        $manager->persist($order1);

        // Création d'une commande pour user2
        $order2 = new Order();
        $order2->setReference('ORD-7894');
        $order2->setCreatedAt(new \DateTime());
        $order2->setStatus(OrderStatus::ANNULEE);
        $order2->setCustomer($user2); // Associe la commande à l'utilisateur 2
        $manager->persist($order2);

        // Création d'une commande pour user3
        $order3 = new Order();
        $order3->setReference('ORD-8974');
        $order3->setCreatedAt(new \DateTime());
        $order3->setStatus(OrderStatus::LIVREE);
        $order3->setCustomer($user3); // Associe la commande à l'utilisateur 3
        $manager->persist($order3);

        // Création de deux items de commande pour la commande de user1
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
