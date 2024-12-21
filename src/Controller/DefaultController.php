<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/hello', name: 'home')]
    public function index(): Response
    {
        $message = 'Bienvenue sur le site des recettes !';
        return $this->render('index.html.twig', [
            'message' => $message,
        ]);
    }

}
