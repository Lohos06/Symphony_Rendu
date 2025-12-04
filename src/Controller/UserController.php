<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route('/login/{id}', name: 'app_login')]
public function login(int $id, UserRepository $repo, Request $request): Response
{
    $user = $repo->find($id);

    if (!$user) {
        throw $this->createNotFoundException('User not found');
    }

    $session = $request->getSession();
    $session->set('role', $user->getRole());
    $session->set('userId', $user->getId());

    return $this->redirectToRoute('app_user');
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
