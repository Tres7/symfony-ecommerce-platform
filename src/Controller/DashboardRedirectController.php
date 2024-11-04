<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



class DashboardRedirectController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard_redirect')]
    public function redirectToDashboard(): Response
    {
        //verify that the user is authenticated
        if ($this->isGranted('ROLE_ADMIN'))
        {
            return $this->redirectToRoute('admin_dashboard');
        }
        elseif ($this->isGranted('ROLE_USER'))
        {
            return $this->redirectToRoute('client_dashboard');
        }

        // redirect to login page if user is not authenticated
        return $this->redirectToRoute('app_login');
    }
}


?>