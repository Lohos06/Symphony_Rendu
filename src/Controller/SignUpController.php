<?php


namespace App\Controller;

use App\Entity\User;
use App\Form\SignUpType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class SignUpController extends AbstractController
{
    #[Route('/sign_up', name: 'app_sign_up')]
public function new(Request $request, EntityManagerInterface $em, UserRepository $repo): Response
{
    $user = new User();

    $form = $this->createForm(SignUpType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // vérifie si email déjà utilisé avant insérer en base
        $existingUser = $repo->findOneBy(['email' => $user->getEmail()]);
        if ($existingUser) {
            $this->addFlash('error', "Cet email est déjà utilisé.");
            return $this->redirectToRoute('app_sign_up');
        }

        // set rôle
        $user->setRole('user');

        // hache mot de passe
        $user->setPasswordHash(
            password_hash($user->getPasswordHash(), PASSWORD_DEFAULT)
        );

        // enregistrement
        $em->persist($user);
        $em->flush();

        // connexion
        $session = $request->getSession();
        $session->set('userId', $user->getId());
        $session->set('role', $user->getRole());

        // redirection -> header maj car user connecté 
        return $this->redirectToRoute('app_home');
    }

    return $this->render('sign_up/index.html.twig', [
        'form' => $form->createView(),
    ]);
}
}