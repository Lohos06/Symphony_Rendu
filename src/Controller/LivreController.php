<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Livre;
use App\Repository\LivreRepository;

final class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]

    public function createLivre(EntityManagerInterface $em): Response
    {
        $livre = new Livre();
        $livre->setAuteurId(1);
        $livre->setGenre(2);
        $livre->setTitre('La femme de Ménage');
        $livre->setDescription("Chaque jour, Millie fait le ménage dans la belle maison des Winchester, une riche famille new-yorkaise. Elle récupère aussi leur fille à l'école et prépare les repas avant d'aller se coucher dans sa chambre, au grenier. Pour la jeune femme, ce nouveau travail est une chance inespérée");
      

        $em->persist($livre);
        $em->flush();

        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
        ]);
    }
}



