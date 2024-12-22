<?php
namespace App\Controller;
use App\Entity\User;
use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
class ResetPasswordController extends AbstractController
{
   #[Route('/reset-password-request', name: 'app_reset_password_request')]
   public function request(Request $request, EntityManagerInterface $entityManager): Response
   {
       $form = $this->createForm(ResetPasswordRequestType::class);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           $email = $form->get('email')->getData();
           $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
               // Générer un token unique
               $token = bin2hex(random_bytes(32));
               $user->setResetToken($token);
               $entityManager->flush();
                // En production, vous enverriez un email ici
               $this->addFlash('success', 'Si un compte existe avec cet email, vous recevrez un lien de réinitialisation. Pour le test, utilisez ce lien : /reset-password/' . $token);
               return $this->redirectToRoute('app_login');
           }
            // Ne pas révéler si l'email existe ou non
           $this->addFlash('success', 'Si un compte existe avec cet email, vous recevrez un lien de réinitialisation.');
       }
        return $this->render('security/request.html.twig', [
           'requestForm' => $form->createView(),
       ]);
   }
    #[Route('/reset-password/{token}', name: 'app_reset_password')]
   public function reset(
       string $token,
       Request $request,
       EntityManagerInterface $entityManager,
       UserPasswordHasherInterface $passwordHasher
   ): Response {
       $user = $entityManager->getRepository(User::class)->findOneBy(['resetToken' => $token]);
        if (!$user) {
           $this->addFlash('danger', 'Token invalide');
           return $this->redirectToRoute('app_login');
       }
        $form = $this->createForm(ResetPasswordType::class);
       $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
           // Effacer le token
           $user->setResetToken(null);
           
           // Hasher et définir le nouveau mot de passe
           $hashedPassword = $passwordHasher->hashPassword(
               $user,
               $form->get('password')->getData()
           );
           $user->setPassword($hashedPassword);
           
           $entityManager->flush();
            $this->addFlash('success', 'Mot de passe modifié avec succès');
           return $this->redirectToRoute('app_login');
       }
        return $this->render('security/reset.html.twig', [
       'resetForm' => $form->createView(),
   ]);
   }
}