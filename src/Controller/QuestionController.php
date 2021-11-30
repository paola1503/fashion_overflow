<?php


namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class QuestionController extends AbstractController
{
    private $logger;
    private $isDebug;

    public function __construct(LoggerInterface $logger, bool $isDebug)
    {
    }

    /**
     * @Route("/", name="app_homepage")
     */
    public function homepage(QuestionRepository $repository)
    {
        $questions=$repository->findAllAskedOrderedByNewest();
        return $this->render('question/homepage.html.twig',[
            'questions'=>$questions,
        ]);
    }

    /**
     * @Route ("/questions/new")
     */

    public function new(EntityManagerInterface $entityManager)
    {
        return new Response(sprintf(
            'Hello!'));
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show(Question $question)
    {
        if ($this->isDebug){
            $this->logger->info('We are in debug mode!');
        }

        $answers = [
            'Wear shoes that are the `same` color as your bag!',
            'If your outfit has a lot of color, wear black or white shoes; if your outfit is made of neutral colors, wear bold shoes.',
            'Invest on high-quality shoes and they will go with everything!'
        ];

        return $this->render('question/show.html.twig', [
            'question' => $question,
            'answers' => $answers,
        ]);
    }

    /**
     * @Route ("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager)
    {
        $direction=$request->request->get('direction');
        if ($direction==='up'){
            $question->upVote();
        }elseif ($direction==='down'){
            $question->downVote();

        }

        $entityManager->flush();

        return $this->redirectToRoute('app_question_show',[
            'slug'=>$question->getSlug(),
        ]);
    }
}
