<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Monolog\DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class CreateBlogController extends AbstractController
{
    #[Route('/create_article', name: 'app_create_blog')]
    public function creteArticle(EntityManagerInterface $entityManager,Request $request ,UserRepository $usersRepository): Response
    {
        $user = new User();
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);
        $user = $entityManager->getRepository(User::class)->findAll();
        for ($i=0; $i < count($user); $i+=1){
            if($user[$i]->getID() != $_COOKIE['LOGGED_IN']){

            }else{
                if ($form->isSubmitted()) {
                    dump($article);
                    $name = $form['name']->getData();
                    $desc = $form['description']->getData();
                    $content = $form['content']->getData();
                    $created = date_create();
                    $article->setUser($user[$i]);
                    $article->setName($name);
                    $article->setDescription($desc);
                    $article->setContent($content);
                    $article->setCreatedAt(DateTimeImmutable::createFromFormat('Y-m-d H-i-s',date('Y-m-d H-i-s')));
                    if (isset($_FILES["image"])){
                        $image = $_FILES['image'];
                        var_dump($image);
                        $article->setImage($image);
                        $entityManager->persist($article);
                        #envoie des informations de la variables #user dans la base de données
                        $entityManager->flush();
                        return $this->redirect($this->generateUrl('home'));
                    } else {
                        $entityManager->persist($article);
                        #envoie des informations de la variables #user dans la base de données
                        $entityManager->flush();
                        return $this->redirect($this->generateUrl('/'));
                    }
 
                }
            }
        }




        return $this->render('create_blog/index.html.twig', [
            'controller_name' => 'CreateBlogController',
            'form' => $form,
        ]);
    }
}
