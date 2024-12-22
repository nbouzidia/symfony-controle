<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted('ROLE_USER')]
class UserProfileController extends AbstractController
{
    #[Route('/profile', name: 'user_profile')]
    public function index(): Response
    {
        // Vérifier si l'utilisateur est banni
        if ($this->isGranted('ROLE_BANNED')) {
            return $this->render('security/banned.html.twig');
        }

        return $this->render('profile.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
