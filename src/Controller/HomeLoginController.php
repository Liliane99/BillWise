<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeLoginController extends AbstractController
{
    #[Route('/home/login', name: 'app_home_login')]
    public function index(): Response
    {
        return $this->render('home_login/index.html.twig', [
            'controller_name' => 'HomeLoginController',
        ]);
    }
}
