<?php

namespace App\DataFixtures;

use App\Entity\Recette;
use App\Entity\User;
use App\Entity\Ingredient;
use App\Entity\Etape;
use App\Entity\Avis;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des utilisateurs
        $roles = [
            'ROLE_ADMIN',
            'ROLE_USER',
            'ROLE_BANNED'
        ];

        $users = [];
        for ($i = 1; $i <= 3; $i++) {
            $user = new User();
            $user->setEmail("user{$i}@example.com");
            $user->setPrenom("Prénom{$i}");
            $user->setNom("Nom{$i}");
            $user->setRoles([$roles[$i - 1]]);
            $hashedPassword = $this->passwordHasher->hashPassword($user, 'password');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
            $users[] = $user; // Garde une référence des utilisateurs pour les relations
        }

        // Création des recettes
        $recettes = [];
        $recetteData = [
            [
                'titre' => 'Tajine de poulet aux olives',
                'description' => 'Un délicieux tajine marocain.',
                'duree' => 90,
                'difficulte' => 'Moyen',
            ],
            [
                'titre' => 'Couscous royal',
                'description' => 'Le plat emblématique de la cuisine maghrébine.',
                'duree' => 120,
                'difficulte' => 'Difficile',
            ],
            [
                'titre' => 'Pastilla aux fruits de mer',
                'description' => 'Une pastilla croustillante et savoureuse.',
                'duree' => 60,
                'difficulte' => 'Facile',
            ],
            [
                'titre' => 'Harira',
                'description' => 'La soupe traditionnelle du ramadan.',
                'duree' => 45,
                'difficulte' => 'Facile',
            ],
            [
                'titre' => 'Chebakia',
                'description' => 'Un dessert sucré et parfumé.',
                'duree' => 120,
                'difficulte' => 'Difficile',
            ],
        ];

        // Création des recettes et des relations avec les utilisateurs
        foreach ($recetteData as $data) {
            $recette = new Recette();
            $recette->setNom($data['titre']);
            $recette->setDescription($data['description']);
            $recette->setDuree($data['duree']);
            $recette->setDifficulte($data['difficulte']);
            $manager->persist($recette);
            $recettes[] = $recette;  // Garde une référence des recettes pour les relations
        }

        // Création des ingrédients
        $ingredientsData = [
            ['nom' => 'Poulet', 'quantite' => 1.5, 'unite' => 'kg'],
            ['nom' => 'Olives', 'quantite' => 200, 'unite' => 'g'],
            ['nom' => 'Semoule', 'quantite' => 500, 'unite' => 'g'],
            ['nom' => 'Tomates', 'quantite' => 6, 'unite' => 'pcs'],
            ['nom' => 'Amandes', 'quantite' => 100, 'unite' => 'g'],
        ];

        foreach ($ingredientsData as $data) {
            $ingredient = new Ingredient();
            $ingredient->setNom($data['nom']);
            $ingredient->setQuantite($data['quantite']);
            $ingredient->setUnite($data['unite']);
            $ingredient->setRecette($recettes[array_rand($recettes)]); // Assignation aléatoire d'une recette

            $manager->persist($ingredient);
        }

        // Création des étapes
        $etapesData = [
            ['description' => 'Faire revenir le poulet dans une cocotte.', 'ordre' => 1],
            ['description' => 'Ajouter les olives et laisser mijoter.', 'ordre' => 2],
            ['description' => 'Ajouter les tomates et cuire à feu doux.', 'ordre' => 3],
        ];

        foreach ($etapesData as $data) {
            $etape = new Etape();
            $etape->setDescription($data['description']);
            $etape->setOrdre($data['ordre']);
            $etape->setRecette($recettes[array_rand($recettes)]); // Assignation aléatoire d'une recette

            $manager->persist($etape);
        }

        // Création des avis
        $avisData = [
            ['note' => 5, 'commentaire' => 'Excellent plat, vraiment savoureux!', 'date' => new \DateTime('2024-12-20')],
            ['note' => 4, 'commentaire' => 'Très bon, mais pourrait être plus épicé.', 'date' => new \DateTime('2024-12-19')],
            ['note' => 3, 'commentaire' => 'Correct, mais un peu fade.', 'date' => new \DateTime('2024-12-18')],
        ];

        foreach ($avisData as $data) {
            $avis = new Avis();
            $avis->setNote($data['note']);
            $avis->setCommentaire($data['commentaire']);
            $avis->setDate($data['date']);
            $avis->setRecette($recettes[array_rand($recettes)]); // Assignation aléatoire d'une recette
            $manager->persist($avis);
        }

        // Sauvegarde en base de données
        $manager->flush();
    }
}
