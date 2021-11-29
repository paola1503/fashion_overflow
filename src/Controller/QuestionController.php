<?php

namespace App\Controller;

use App\Entity\Question;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Sentry\State\HubInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function homepage(Environment $twigEnvironment)
    {
        /*
        // fun example of using the Twig service directly!
        $html = $twigEnvironment->render('question/homepage.html.twig');

        return new Response($html);
        */

        return $this->render('question/homepage.html.twig');
    }

    /**
     * @Route ("/questions/new")
     */

    public function new(EntityManagerInterface $entityManager)
    {
        $question=new Question();
        $question->setName('White dress for a wedding')
            ->setSlug('white-dress-for-a-wedding-'.rand(0,1000))
            ->setQuestion(<<<EOF
Hi everyone! I've been invited to a wedding and I've found a beautiful dress for the occasion, but I don't know if it's "too white". Its main color is indeed white, but it has golden dots all over it. Do you think I can wear it, or is the bride going to be angry at me?
EOF
);
        if (rand(1,10)>2){
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1,100))));
        }
        $entityManager->persist($question);
        $entityManager->flush();
        return new Response(sprintf(
            'Hello! The new question is id #%d, slug %s',
            $question->getId(),
            $question->getSlug(),
        ));
    }

    /**
     * @Route("/questions/{slug}", name="app_question_show")
     */
    public function show($slug, MarkdownHelper $markdownHelper, EntityManagerInterface $entityManager)
    {
        if ($this->isDebug){
            $this->logger->info('We are in debug mode!');
        }

        $repository=$entityManager->getRepository(Question::class);
        /** @var Question|null $question */
        $question=$repository->findOneBy(['slug'=>$slug]);
        if (!$question){
            throw $this->createNotFoundException(sprintf('No question found for slug "%s', $slug));
        }
        dd($question);

        $answers = [
            'Wear shoes that are the `same` color as your bag!',
            'If your outfit has a lot of color, wear black or white shoes; if your outfit is made of neutral colors, wear bold shoes.',
            'Invest on high-quality shoes and they will go with everything!'
        ];

        $questionText='I usually wear the same pair of shoes but would like to *elevate* my outfit by wearing **shoes that match**. Any tips?';
        $parsedQuestionText=$markdownHelper->parse($questionText);

        return $this->render('question/show.html.twig', [
            'question' => ucwords(str_replace('-', ' ', $slug)),
            'questionText' => $parsedQuestionText,
            'answers' => $answers,
        ]);
    }
}
