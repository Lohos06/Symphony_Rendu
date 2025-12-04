<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreRepository; 
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(LivreRepository $repo): Response
    {
        $livres = $repo->findAll();

        return $this->render('livre/list.html.twig', [
            'livres' => $livres,
        ]);
    }

    #[Route('/livre/add', name: 'app_livre_add')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $session = $request->getSession();

        if ($session->get('role') !== 'admin') {
            return $this->redirectToRoute('app_user');
        }

        // crée new livre
        $livre = new Livre();

        // Créa du form lié au livre
        $form = $this->createForm(LivreType::class, $livre);

        // lit la requete
        $form->handleRequest($request);

        // si form soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {

            // enregistre en BDD
            $em->persist($livre);
            $em->flush();

            // redirige vers la liste des livres
            return $this->redirectToRoute('app_livre');
        }

        // Affichage du form
        return $this->render('livre/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
