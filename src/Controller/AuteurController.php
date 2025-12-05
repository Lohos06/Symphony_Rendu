<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class AuteurController extends AbstractController
{
    #[Route('/auteur', name: 'app_auteur')]
    public function index(AuteurRepository $repo): Response
    {
        $auteurs = $repo->findAll();

        return $this->render('auteur/list.html.twig', [
            'auteurs' => $auteurs,
        ]);
    }

    #[Route('/auteur/add', name: 'app_auteur_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();
        if ($session->get('role') !== 'admin') {
        
            return $this->redirectToRoute('app_user');
        }

        // crée new auteur
        $auteur = new Auteur();

        // Créa du form lié au auteur
        $form = $this->createForm(AuteurType::class, $auteur);

        // lit la requete
        $form->handleRequest($request);

        // si form soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // enregistre en BDD
            $em->persist($auteur);
            $em->flush();

            // redirige vers la liste des auteurs
            return $this->redirectToRoute('app_auteur');
        }

        // Affichage du form
        return $this->render('auteur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}