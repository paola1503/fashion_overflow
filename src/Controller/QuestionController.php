<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     */
public function homepage(Environment $twigEnvironment)
{
    //Example of using the Twig service directly
    /*$html = $twigEnvironment->render('question/homepage.html.twig');

    return new Response($html);*/

    return $this->render('question/homepage.html.twig');
}
    /**
     * @Route("/questions/{question}", name="app_question_show")
     */
public function show($question)
{
    $answers=[
        'Always choose black or white so you can never go wrong',
        'Choose the same color as your bag',
        'Better focus on whether they are comfortable or not',
    ];

    dump($this);

    return $this->render('question/show.html.twig',[
        'question'=>ucwords(str_replace('-',' ',$question)),
        'answers'=>$answers,
    ]);

}
}