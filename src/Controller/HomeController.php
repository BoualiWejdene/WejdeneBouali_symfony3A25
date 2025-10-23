<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\MessageGenerator;

final class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController', 'identifiant' => 5
        ]);
    }

    #[Route('/', name: 'home')]
    public function home(): Response
    {
        return $this->render('home/home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route(path: '/hello/{id}',name:'hello')]
    public function hello($id){
        return new Response("Hello 3A25".$id);
        //return $this->render('home/index.html.twig',['identifiant' => 5]);
    }

    #[Route('/message', name: 'message')]
    public function message(MessageGenerator $messageGenerator): Response{
        $message = $messageGenerator->getHappyMessage();
        return new Response("<h1>Citation du jour :</h1><p>$message</p>");
    } 
}

