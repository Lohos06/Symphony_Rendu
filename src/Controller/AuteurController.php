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
    public function index(Request $request, AuteurRepository $repo): Response
    {
        //  Vérification connexion
        $session = $request->getSession();
        if (!$session->get('userId')) {
            return $this->redirectToRoute('app_home');
        }

        // Récupère tous les auteurs
        $auteurs = $repo->findAll();

        return $this->render('auteur/list.html.twig', [
            'auteurs' => $auteurs,
        ]);
    }

    #[Route('/auteur/add', name: 'app_auteur_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();

        // Vérification si connecté
        if (!$session->get('userId')) {
            return $this->redirectToRoute('app_home');
        }

        // Vérification admin
        if ($session->get('role') !== 'admin') {
            return $this->redirectToRoute('app_auteur');
        }

        // Création nouvel auteur
        $auteur = new Auteur();

        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($auteur);
            $em->flush();

            return $this->redirectToRoute('app_auteur');
        }

        return $this->render('auteur/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}