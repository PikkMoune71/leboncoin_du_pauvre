<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Article;
use App\Form\AnswerType;
use App\Form\ArticleType;
use App\Form\NewArticleType;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class AccountController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/account", name="account")
     */
    public function index(): Response
    {
        return $this->render('account/index.html.twig');
    }

    /**
     * @Route("/account/articles", name="account_articles")
     */
    public function viewArticles(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        return $this->render('account/articles.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * @Route("/account/articles/new", name="account_article_new")
     */
    public function newArticle(Request $request): Response
    {
        $article = new Article();

        $formNewArticle = $this->createForm(NewArticleType::class, $article);

        $formNewArticle->handleRequest($request);

        if($formNewArticle->isSubmitted() && $formNewArticle->isValid()) {
            $article = $formNewArticle->getData();

            $slugger = new AsciiSlugger();
            $article->setSlug($slugger->slug($article->getName()));
            $article->setCreatedAt(new \DateTime('now'));
            $article->setUsers($this->getUser());

            $this->entityManager->persist($article);
            $this->entityManager->flush();

            return $this->redirectToRoute('account_articles');
        }
        return $this->render('account/new.html.twig', [
            'formNewArticle' => $formNewArticle->createView()
        ]);
    }

    /**
     * @Route("/account/articles/{slug}", name="app_account_article_edit")
     */
    public function editArticle(Article $article, $slug, Request $request, EntityManagerInterface $entityManager ): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $article Article
             */
            $article = $form->getData();

            $slugger = new AsciiSlugger();
            $article->setSlug($slugger->slug($article->getName()));

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('account_articles');
        }
        return $this->render('account/edit.html.twig', [
            'formArticle' => $form->createView(),
        ]);
    }

    /**
     * @Route("/account/{slug}/{id}/delete", name="app_account_article_delete")
     */
    public function articleDelete($id, $slug, EntityManagerInterface $entityManager)
    {
        $articleSlug = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        $article = $entityManager->getReference(Article::class, $id);
        $entityManager->remove($article);
        $entityManager->flush();

        return $this->redirectToRoute('account_articles');
    }
}
