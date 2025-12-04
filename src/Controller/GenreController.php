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
    public function index(GenreRepository $repo): Response
    {
        $genres = $repo->findAll();

        return $this->render('genre/list.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/genre/add', name: 'app_genre_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();

        if ($session->get('role') !== 'admin') {
            return $this->redirectToRoute('app_user');
        }

        // crée new genre
        $genre = new Genre();

        // Créa du form lié au genre
        $form = $this->createForm(GenreType::class, $genre);

        // lit la requete
        $form->handleRequest($request);

        // si form soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // enregistre en BDD
            $em->persist($genre);
            $em->flush();

            // redirige vers la liste des genres
            return $this->redirectToRoute('app_genre');
        }

        // Affichage du form
        return $this->render('genre/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
