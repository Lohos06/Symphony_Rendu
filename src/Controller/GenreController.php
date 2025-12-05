<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class GenreController extends AbstractController
{
    #[Route('/genre', name: 'app_genre')]
    public function index(Request $request, GenreRepository $repo): Response
    {
        $session = $request->getSession();

        
        if (!$session->get('userId')) {
            return $this->redirectToRoute('app_home');
        }

        //  Récup genres
        $genres = $repo->findAll();

        return $this->render('genre/list.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/genre/add', name: 'app_genre_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();

        // NON CONNECTÉ → accès refusé
        if (!$session->get('userId')) {
            return $this->redirectToRoute('app_home');
        }

        //  NON ADMIN → pas le droit
        if ($session->get('role') !== 'admin') {
            return $this->redirectToRoute('app_genre');
        }

        //  Création genre
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($genre);
            $em->flush();
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}