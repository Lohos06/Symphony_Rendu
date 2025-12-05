<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SignUpController extends AbstractController
{
    #[Route('/sign_up', name: 'app_sign_up')]
    public function new(Request $request, EntityManagerInterface $em): Response
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
            
            
             //connecte automatiquement l'utilisateur
            $session = $request->getSession();
            $session->set('userId', $user->getId());
            $session->set('role', $user->getRole());
            return $this->redirectToRoute('app_home');

        }

        // sinon on affiche le formulaire
        return $this->render('sign_up/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
