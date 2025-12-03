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
     public function createGenre(EntityManagerInterface $em): Response
    {
        $genre = new Genre();
        $genre->setNom('Thriller');

        $em->persist($genre);
        $em->flush();

        return $this->render('genre/index.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }
}
