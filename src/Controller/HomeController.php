<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request): Response
    {
        // Récupération session
        $session = $request->getSession();

        // si la personne n'est pas connectée : on affiche la page calassique
        if (!$session->get('userId')) {
            return $this->render('home/index.html.twig');
        }

         return $this->render('home/index.html.twig');
    }
}
