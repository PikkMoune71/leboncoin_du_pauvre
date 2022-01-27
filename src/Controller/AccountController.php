<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Article;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
}
