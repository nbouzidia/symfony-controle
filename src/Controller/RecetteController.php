<?php

namespace App\Controller;

use App\Repository\RecetteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecetteController extends AbstractController
{
    #[Route('/recette',name: 'app_recettes')]
    public function index(RecetteRepository $recetteRepository): Response
    {
        return $this->render('recette.html.twig', [
            'recettes' => $recetteRepository->findAll(),
        ]);
    }
}
