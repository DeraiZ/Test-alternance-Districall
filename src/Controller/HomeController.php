<?php



namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        if (isset($_COOKIE['LOGGED_IN']) == true){
            $hidden = 'hidden';
        }else{
            $hidden = ' ';
        }
        #get all fromarticle database
        $article = $entityManager->getRepository(Article::class)->findAll();
        $i = 0;
        $array = [[]];
        while($i < count($article)){
            $name = $article[$i]->getName();
            $description = $article[$i]->getDescription();
            $content = $article[$i]->getContent();
            $createdat = $article[$i]->getCreatedAt();
            $date = $createdat->format('d-m-Y, H-i-s');

            $i+=1;
        }

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'articles' => $article,
            'date' => $date,
            'hidden' => $hidden
        ]);
    }
}
