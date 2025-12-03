<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\User;
use App\Repository\UserRepository;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(UserRepository $UserRepository): Response
    {
        $users = $UserRepository->getUsers();
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
            'users' => $users,
        ]);
    }

    #[Route('/user/add', name: 'app_userAdd')]
    public function createUser(EntityManagerInterface $em): Response
    {
        $user = new User();
        $user->setName('Lorenzo');
        $user->setEmail('Lorenzo@gmail.com');
        $user->setPasswordHash(password_hash('Lorenzo', PASSWORD_DEFAULT));
        $user->setRole('admin');
        $user->setDescription('Fan de fantasy, Histoire et strategie');

        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute('app_user');
    }
}
