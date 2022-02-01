<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Question;
use App\Form\QuestionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("question/{slug}/{id}/edit", name="app_question_edit")
     */
    public function edit(Question $question, $slug, Request $request, EntityManagerInterface $entityManager)
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        $form = $this->createForm(QuestionType::class, $question);

        // Le reste ne change pas
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $question Question
             */
            $question = $form->getData();

            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('article', ['slug' => $article->getSlug()]);
        }

        return $this->render('question/index.html.twig', [
            'formQuestion' => $form->createView(),
        ]);
    }

    /**
     * @Route("/question/{slug}/{id}/delete", name="app_question_delete")
     */
    public function questionDelete($id, $slug, EntityManagerInterface $entityManager)
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        // getReference ne marche qu'avec un id
        $question = $entityManager->getReference(Question::class, $id);
        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('article', ['slug' => $article->getSlug()]);
    }
}
