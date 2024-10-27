<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;


class ErrorController extends AbstractController
{
    #[Route('/403', name: 'error_403')]
    public function accessDenied(): Response
    {
        return $this->render('bundles/TwigBundle/Exception/403.html.twig');
    }

}

?>