<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;

final class UserController extends AbstractController
{ 
    //page affichant la liste des users (ADMIN uniquement)
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $UserRepository, Request $request): Response
    {
        // récupération session
        $session = $request->getSession();

        // vérifier si connecté
        if (!$session->get('userId')) {
            return $this->redirectToRoute('app_home');
        }

        //  vérifier si admin
        if ($session->get('role') !== 'admin') {
            $this->addFlash('error', "Hop hop hop ! Vous n'avez pas accès à cette page.");
            return $this->redirectToRoute('app_home');
        }

        // si admin → récupérer la liste des utilisateurs
        $users = $UserRepository->getUsers();

        //  afficher twig
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }
}
