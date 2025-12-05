<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


final class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(Request $request, UserRepository $repo): Response
    {
        $form = $this->createForm(LoginType::class);

        $form->handleRequest($request);
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = $data['email'];
            $password = $data['password'];

            // Récupérer user par email
            $user = $repo->findOneBy(['email' => $email]);

            //si le mp pas = mp dans bdd : hop 
            if (!$user || !password_verify($password, $user->getPasswordHash())) {
                $error = "Email ou mot de passe incorrect.";
            } else {

               // Créer la session
                $session = $request->getSession();
                $session->set('userId', $user->getId());
                $session->set('role', $user->getRole());

                // redirige après connexion
                return $this->redirectToRoute('app_home');
            }
        }

        return $this->render('login/index.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request): Response
    {
        $session = $request->getSession();
        $session->clear(); // Supprime les infos de connexion

        return $this->redirectToRoute('app_home');
    }
}