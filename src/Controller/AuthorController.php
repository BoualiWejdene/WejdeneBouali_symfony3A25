<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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

    #[Route('/addStat',name:'addStat')]
    public function addSat(ManagerRegistry $doctrine){
        $author =new Author();
        $author->setEmail('Test@gmail.com');
        $author->setUsername('Foulen');
        $em =  $doctrine->getManager();
        $em->persist($author);
        $em->flush();
        //return new Response("Author added succesfully");
        return $this->redirectToRoute("showAll");

    }

    
    #[Route('/deleteAuthor/{id}',name:'deleteAuthor')]
    public function deleteAuthor($id,AuthorRepository $repo, ManagerRegistry $manager){
        $author = $repo->find($id);
        $em =  $manager ->getManager();
        $em->remove($author);
        $em->flush();
       
        return $this->redirectToRoute("showAll");

    }

    #[Route('/showAuthorDetails/{id}',name:'showAuthorDetails')]
    public function showAuthorDetails($id,AuthorRepository $repo){
        $author = $repo->find($id);
        return $this->render('author/showDetails.html.twig', ['author' =>$author]);

    }
    
    #[Route('/addForm',name:'addForm')]
    public function addForm(ManagerRegistry $doctrine,Request $request){
        $author =new Author();
        $form = $this->createForm(AuthorType::class,$author);
        $form->add('add',SubmitType::class);
        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em->persist($author);
            $em->flush();

            return $this->redirectToRoute("showAll");

        }
        return $this->render('author/add.html.twig', ['formulaire' => $form->createView()]);

    }

    #[Route('/editForm/{id}',name:'editForm')]
    public function editForm($id,AuthorRepository $repo,Request $request,ManagerRegistry $doctrine){
        $author = $repo->find($id);
        $form = $this->createForm(AuthorType::class,$author);
        $form->add('update',SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $em = $doctrine->getManager();
            $em->flush();

            return $this->redirectToRoute("showAll");

        }
        return $this->render('author/edit.html.twig',['formulaire' => $form->createView()]);
    }

    #[Route('/ShowAllAuthorQB',name:'ShowAllAuthorQB')]
    public function ShowAllAuthorQB(AuthorRepository $repo){
        $authors = $repo->showAllQB();
        return $this-> render('author/showAll.html.twig',['list' => $authors]);
    }

    

}
