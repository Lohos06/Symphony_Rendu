<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;

final class UserController extends AbstractController
{
    // Page affichant la liste des user (ADMIN uniquement)
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $UserRepository, Request $request): Response
    {
        $session = $request->getSession();


        // si Admin → afficher la liste des users
        $users = $UserRepository->getUsers();

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    
}