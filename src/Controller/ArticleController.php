<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Article;
use App\Entity\Question;
use App\Entity\User;
use App\Form\QuestionType;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class ArticleController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/articles", name="articles")
     */
    public function index(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();

        $search = new Search();
        $form = $this->createForm(SearchType::class, $search);

        if ($form->isSubmitted() && $form->isValid()){
            $articles = $this->entityManager->getRepository(Article::class)->findWithSearch($search);
        }else{
            $articles = $this->entityManager->getRepository(Article::class)->findAll();
        }

        return $this->render('article/index.html.twig', [
            'articles' => $articles,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/{slug}", name="article")
     */
    public function show($slug, Request $request): Response
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        if(!$article){
            return $this->redirectToRoute('articles');
        }

        $question = new Question();
        $formQuestion = $this->createForm(QuestionType::class, $question);

        $formQuestion->handleRequest($request);

        if($formQuestion->isSubmitted() && $formQuestion->isValid()){
            $question = $formQuestion->getData();

            $slugger = new AsciiSlugger();
            $question->setSlug($slugger->slug($question->getName()));
            $question->setTaskAt(new \DateTime('now'));
            $question->setUser($this->getUser());
            $question->setArticle($article);

            $this->entityManager->persist($question);
            $this->entityManager->flush();

            return $this->redirectToRoute('articles');
        }

        return $this->render('article/show.html.twig', [
            'article' => $article,
            'formQuestion' => $formQuestion->createView(),
            'slug' => $article->getSlug(),
        ]);
    }

    /**
     * @Route("/article/{slug}/{id}/vote", name="app_user_vote", methods={"POST"})
     */
    public function userVote(User $user, $slug, Request $request, EntityManagerInterface $entityManager): RedirectResponse
    {
        $vote = $request->request->get('vote');
        $article = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        if ($vote === "up") {
            $user->upVote();
        } else {
            $user->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('article', [
            'id' => $user->getId(),
            'slug' => $article->getSlug()
        ]);
    }
}
