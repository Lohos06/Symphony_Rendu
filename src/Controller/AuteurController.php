<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;


final class AuteurController extends AbstractController
{
 
    #[Route('/auteur', name: 'app_auteur')]
    public function index(AuteurRepository $AuteurRepository): Response
    {
        $auteurs = $AuteurRepository->getUsers();
        return $this->render('auteur/index.html.twig', [
            'controller_name' => 'AuteurController',
            'auteurs' => $auteurs,
        ]);
    }




    #[Route('/auteur', name: 'app_auteur')]
    public function createAuteur(EntityManagerInterface $em): Response
    {
        $auteur = new Auteur();
        $auteur->setNom('Freida McFadden');
        $auteur->setSiecle('XXIème');
        $auteur->setStyle('200 aura');
      
      

        $em->persist($auteur);
        $em->flush();

        return $this->render('auteur/index.html.twig', [
            'controller_name' => 'AuteurController',
        ]);
    }
}
