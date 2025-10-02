<?php

namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }
    

    #[Route('/show/{name}',name:'showAuthor')]
    public function showAuthor($name){
        return $this->render('author/show.html.twig',['nom' => $name, 'prenom' => 'ben foulen']);
    }


    #[Route('/authors',name:'listAuthors')]
    public function listAuthors(){
        $authors = array(
            array('id' => 1, 'picture' => 'assets/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => 'assets/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => 'assets/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render('author/list.html.twig',['auteurs' => $authors]);
    }

    #[Route('/authors/{id}',name:'authorDetails')]
    public function authorDetails($id){
        $authors = array(
            array('id' => 1, 'picture' => 'assets/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' => 'victor.hugo@gmail.com ', 'nb_books' => 100),
            array('id' => 2, 'picture' => 'assets/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>  ' william.shakespeare@gmail.com', 'nb_books' => 200 ),
            array('id' => 3, 'picture' => 'assets/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' => 'taha.hussein@gmail.com', 'nb_books' => 300),
        );
        return $this->render('author/showAuthor.html.twig',['id' => $id , 'authors' => $authors]);
    
    }

    #[Route('/showAll',name:'showAll')]
    public function showAll(AuthorRepository $repo){
        $auteurs = $repo->findAll();
        return $this-> render('author/showAll.html.twig',['list' => $auteurs]);

    }
    
}
