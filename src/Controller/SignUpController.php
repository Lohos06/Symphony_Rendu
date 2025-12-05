<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SignUpController extends AbstractController
{
    #[Route('/sign_up', name: 'app_sign_up')]
    public function new(Request $request, EntityManagerInterface $em, UserRepository $repo): Response
    {
        // on crée un new user
        $user = new User();

        // crée form lié à $user
        $form = $this->createForm(SignUpType::class, $user);

        // lit la requête 
        $form->handleRequest($request);

        // si le form est envoyé et valide
        if ($form->isSubmitted() && $form->isValid()) {
            

            $user->setRole('user');
            // on hache le mp
            $user->setPasswordHash(
                password_hash($user->getPasswordHash(), PASSWORD_DEFAULT)
            );

            // enregistre dans la BDD
            $em->persist($user);
            $em->flush();
            
            // Vérifier si email déjà utilisé
            $existingUser = $repo->findOneBy(['email' => $user->getEmail()]);
            if ($existingUser) {
                $this->addFlash('error', "Cet email est déjà utilisé.");
                return $this->redirectToRoute('app_sign_up');
            }
            
            

        }

        // sinon on affiche le formulaire
        return $this->render('sign_up/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}