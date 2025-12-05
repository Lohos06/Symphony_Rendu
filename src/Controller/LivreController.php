<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use App\Repository\LivreRepository;
use App\Repository\AuteurRepository;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(
        LivreRepository $repo, 
        AuteurRepository $aRepo, 
        GenreRepository $gRepo
    ): Response
    {
        $livres = $repo->findAll();
        $auteurs = $aRepo->findAll();
        $genres = $gRepo->findAll();

        return $this->render('livre/list.html.twig', [
            'livres'  => $livres,
            'auteurs' => $auteurs,
            'genres'  => $genres,
        ]);
    }

    #[Route('/livre/add', name: 'app_livre_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();

        // Vérification admin
        if ($session->get('role') !== 'admin') {
            return $this->redirectToRoute('app_user');
        }

        // Création du livre
        $livre = new Livre();

        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupérer auteur choisi
            $auteur = $form->get('auteur')->getData();
            if ($auteur) {
                $livre->setAuteur($auteur->getId());
            }

            // Récupérer genre choisi
            $genre = $form->get('genre')->getData();
            if ($genre) {
                $livre->setGenre($genre->getId());
            }

            // Sauvegarde
            $em->persist($livre);
            $em->flush();

            return $this->redirectToRoute('app_livre');
        }

        return $this->render('livre/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
