<?php

namespace App\Controller;


use App\Form\UserType;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SignInController extends AbstractController
{
    #[Route('/sign_in', name: 'app_user')]
    public function createUser(EntityManagerInterface $entityManager,Request $request ,UserRepository $usersRepository): Response
    {
        #creation d'un nouvelle utilisateur
        $user = new User();
        #création d'un formulaire d'inscription
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        #verification de la submition et la validité du formulaire
        if ($form->isSubmitted()  && $form->isValid()) {
            #faire le vide de la variable $user
            dump($user);
            #gérer l'instance de la variable $user
            $entityManager->persist($user);
            #envoie des informations de la variables #user dans la base de données
            $entityManager-> flush();
            $this->redirect('http://localhost:8001/connexion');
        }


        return $this->render('sign_in/index.html.twig', [
            'form' => $form,
        ]);
    }
}

