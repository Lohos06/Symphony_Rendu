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
        // Auteur et genre -> IDs → on récupère tout pour faire la correspondance
        $livres  = $repo->findAll();
        $livres  = $repo->findAll();
        $auteurs = $aRepo->findAll();
        $genres  = $gRepo->findAll();

        // envoie les livres / les listes d'auteurs/genres au template
        // pour pouvoir afficher le nom correspondant à chaque ID
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

            // Récupération des données envoyées par les champs non mappés
            $auteur = $form->get('auteur')->getData();
            $genre  = $form->get('genre')->getData();

            // Vérification pour pas erreur
            if (!$auteur || !$genre) {
                $this->addFlash('error', 'Veuillez sélectionner un auteur et un genre.');
                return $this->redirectToRoute('app_livre_add');
            }

            // On applique  IDs dans l'entité (car ce sont des IDs dans la bdd, pas des relations)
            $livre->setAuteur($auteur->getId());
            $livre->setGenre($genre->getId());

            // Sauvegarde en BDD
            $em->persist($livre);
            $em->flush();

            return $this->redirectToRoute('app_livre');
        }

        return $this->render('livre/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
