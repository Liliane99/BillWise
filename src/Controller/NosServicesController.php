<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NosServicesController extends AbstractController
{
    #[Route('/NosServices', name: 'app_nos_services')]
    public function index(): Response
    {
        return $this->render('nos_services/index.html.twig', [
            'controller_name' => 'NosServicesController',
        ]);
    }
}
