<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ArticleType;

class BlogController extends AbstractController
{
    #[Route("/blog", name: "blog")]
    // on passe le repository dans la variable $repo
    public function index(ArticleRepository $repo): Response

    {
        $articles = $repo->findAll();
        return $this->render('blog/index.html.twig', [

            'controller_name' => 'BlogController',
            'articles' => $articles
        ]);
    }

    #[Route("/", name: "home")]
    public function home(): Response
    {
        return $this->render('blog/home.html.twig');
    }

    #[Route("/blog/{id<\d+>}", name: "blog_show")] // on spécifie que id doit être un nombre 
    public function show(Article $article)
    {
        //même chose que ceci en simplifié, il comprend tout seul avec paramconverter qu'il doit envoyer l'article qui a l'$id envoyer
        // public function show(ArticleRepository $repo,$id){

        // $article = $repo->find($id);

        return $this->render('blog/show.html.twig', [
            'article' => $article
        ]);
    }

    #[Route("/blog/new", name: "blog_create")]
    #[Route("/blog/{id<\d+>}/edit", name: "blog_edit")]

    public function form(Article $article = null, Request $request, EntityManagerInterface $manager)
    {
        if (!$article) {
            $article = new Article();
        }

        //création du form dans form\ArticleType
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request); // regarde si les données sont presente et les Bind

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$article->getId()) {
                $article->setCreatedAt(new \DateTime());
            }

            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getid()]);
        }
        return $this->render('blog/create.html.twig', [
            'formArticle' => $form->createView(),
            'editMode' => $article->getId() !== null
        ]);
    }
}
