<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Article;
use App\Entity\Question;
use App\Form\AnswerType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/answer/{id}/{slug}", name="app_answer")
     */
    public function index(Request $request, $slug, $id): Response
    {
        $question = $this->entityManager->getRepository(Question::class)->findOneBySlug($slug);
        $questionId = $this->entityManager->getRepository(Question::class)->findOneById($id);

        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $answer = $form->getData();

            $answer->setAnswerAt(new \DateTime('now'));
            $answer->setUser($this->getUser());
            $answer->setQuestion($questionId);

            $this->entityManager->persist($answer);
            $this->entityManager->flush();

            return $this->redirectToRoute('articles');
        }


        return $this->render('answer/index.html.twig', [
            'formAnswer' => $form->createView(),
            'slug' => $question->getSlug(),
            'id' => $questionId->getId(),
        ]);
    }

    /**
     * @Route("/answer/{slug}/{id}/delete", name="app_answer_delete")
     */
    public function answerDelete($id, $slug, EntityManagerInterface $entityManager)
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        // getReference ne marche qu'avec un id
        $answer = $entityManager->getReference(Answer::class, $id);
        $entityManager->remove($answer);
        $entityManager->flush();

        return $this->redirectToRoute('article', ['slug' => $article->getSlug()]);
    }



    /**
     * @Route("answer/{slug}/{id}/edit", name="app_answer_edit")
     */
    public function edit(Answer $answer, $slug, Request $request, EntityManagerInterface $entityManager)
    {
        $article = $this->entityManager->getRepository(Article::class)->findOneBySlug($slug);

        $form = $this->createForm(AnswerType::class, $answer);

        // Le reste ne change pas
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $answer Answer
             */
            $answer = $form->getData();

            $entityManager->persist($answer);
            $entityManager->flush();

            return $this->redirectToRoute('article', ['slug' => $article->getSlug()]);
        }

        return $this->render('answer/index.html.twig', [
            'formAnswer' => $form->createView(),
        ]);
    }

}
