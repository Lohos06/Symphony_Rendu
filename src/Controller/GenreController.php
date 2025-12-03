<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Genre;
use App\Repository\GenreRepository;

final class GenreController extends AbstractController
{
    #[Route('/genre', name: 'app_genre')]
    public function index(GenreRepository $GenreRepository): Response
    {
        $genres = $GenreRepository->getGenres();
        return $this->render('Genre/index.html.twig', [
            'controller_name' => 'GenreController',
            'genres' => $genres,
        ]);
    }

    #[Route('/genre/add', name: 'app_genreAdd')]
     public function createGenre(EntityManagerInterface $em): Response
    {
        $genre = new Genre();
        $genre->setNom('Thriller');

        $em->persist($genre);
        $em->flush();

        return $this->redirectToRoute('app_genre');
    }
}
