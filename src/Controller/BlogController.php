<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;

class BlogController extends AbstractController
{
    #[Route("/blog", name: "blog")]
    public function index(ArticleRepository $repo): Response
    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [
            
            'controller_name' => 'BlogController',
            'articles'=>$articles
        ]);
    }

    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig');
    }
    #[Route("/blog/{id}",name:"blog_show")]
    public function show(Article $article){
    // public function show(ArticleRepository $repo,$id){

        // $article = $repo->find($id);
        
        return $this->render('blog/show.html.twig',[
            'article'=>$article
        ]);
    }
}
