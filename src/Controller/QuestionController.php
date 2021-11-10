<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/")
     */
public function homepage()
{
    return new Response('What a cute controller we have created!');
}
    /**
     * @Route("/questions/{question}")
     */
public function show($question)
{
    $answers=[
        'Always choose black or white so you can never go wrong',
        'Choose the same color as your bag',
        'Better focus on whether they are comfortable or not',
    ];

    return $this->render('question/show.html.twig',[
        'question'=>ucwords(str_replace('_',' ',$question)),
        'answers'=>$answers,
    ]);

}
}