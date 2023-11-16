<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'connexion')]
    public function connexion(EntityManagerInterface $entityManager,Request $request ,UserRepository $usersRepository): Response
    {
        $user = new User();
        $id=0;
        $var='';
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $mail = $form['email']->getData();
            $repo = $entityManager -> getRepository(User::class);
            $all = $repo->findAll();
            $pass = $form['password']->getData();
            while ($id < count($all)){
                $email = $repo->findAll()[$id]->getEmail();
                $userid = $repo->findAll()[$id]->getID();
                if ($mail == $email){
                    $pwd = $repo->findAll()[$id]->getPassword();
                    if ($pass == $pwd){
                        $var = 'ok';
                        setcookie('LOGGED_IN', $userid,[
                            'expires' => time() + 24*3600
                        ]);

                        return $this->redirect($this->generateUrl('home'));
                    } else{
                        $var = 'E-mail ou mot de passe incorrecte';
                        break;
                    }
                }elseif ($mail != $email){
                    $var = 'E-mail ou mot de passe incorrecte';
                    $id+=1;
                }
            }}




        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'form' => $form,
            'user' => $var,
            "id" => $id
        ]);
    }}
