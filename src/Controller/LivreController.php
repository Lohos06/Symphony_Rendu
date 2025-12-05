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
        Request $request, // permet d'accéder à la session 
        LivreRepository $repo,  // pour récup les livres
        AuteurRepository $aRepo,   // pour récup les auteurs
        GenreRepository $gRepo  // pour récupérer les genres
    ): Response
    {
        // verif connexion
        $session = $request->getSession();
        if (!$session->get('userId')) {
            return $this->redirectToRoute('app_home');
        }

        /* récupère toutes les entrées dans la table Livre, Auteur et Genre.
        envoie tout au template, car les livres contiennent seulement les IDs d'auteur et de genre (pas objets).*/
       
        $livres  = $repo->findAll();
        $auteurs = $aRepo->findAll();
        $genres  = $gRepo->findAll();

        return $this->render('livre/list.html.twig', [
            'livres'  => $livres,
            'auteurs' => $auteurs,
            'genres'  => $genres,
        ]);
    }

    #[Route('/livre/add', name: 'app_livre_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // session on demarre
        $session = $request->getSession();

        // vérif admin
        if ($session->get('role') !== 'admin') {
            return $this->redirectToRoute('app_user');
        }

        // création du livre
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $auteur = $form->get('auteur')->getData();
            $genre  = $form->get('genre')->getData();

            if (!$auteur || !$genre) {
                $this->addFlash('error', 'Veuillez sélectionner un auteur et un genre.');
                return $this->redirectToRoute('app_livre_add');
            }

            // iDs stockés directement dans la table Livre
            $livre->setAuteur($auteur->getId());
            $livre->setGenre($genre->getId());

            // enregistrement
            $em->persist($livre);
            $em->flush();

            return $this->redirectToRoute('app_livre');
        }

        return $this->render('livre/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
