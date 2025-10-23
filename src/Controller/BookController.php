<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/addBook',name:'addBook')]
    public function addBook(ManagerRegistry $doctrine,Request $request){
        $book =new Book();
        $book->setpublished(true);
        $form = $this->createForm(BookType::class,$book);
        $form->add('save',SubmitType::class);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $author = $book->getAuthor();
            $author->setNbBooks($author->getNbBooks() + 1);

            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();

            return $this->redirectToRoute("showBooks");

        }
        return $this->render('Book/addBook.html.twig', ['formulaire' => $form->createView()]);

    }

    #[Route('/showBooks',name:'showBooks')]
    public function showBooks(BookRepository $repo){
        $publishedBooks = $repo->findBy(['published' => true]);
        $nbPublished = count($repo->findBy(['published' => true]));
        $nbUnpublished = count($repo->findBy(['published' => false]));

        return $this->render('Book/showBooks.html.twig', ['publishedBooks' => $publishedBooks ,'nbPublished' => $nbPublished ,'nbUnpublished' => $nbUnpublished]);
        
    }

    #[Route('/editBook/{id}',name:'editBook')]
    public function editBook($id,BookRepository $repo,Request $request,ManagerRegistry $doctrine){
        $book = $repo->find($id);
        $oldAuthor = $book->getAuthor();

        $form = $this->createForm(BookType::class,$book);
        $form->add('save',SubmitType::class);
        $form->add('published', CheckboxType::class, [
            'required' => false,
        ]);

        $form->handleRequest($request);
        if($form->isSubmitted()){
            $newAuthor = $book->getAuthor();
            if ($oldAuthor && $oldAuthor !== $newAuthor) {
                $oldAuthor->setNbBooks($oldAuthor->getNbBooks() - 1);
                $newAuthor->setNbBooks($newAuthor->getNbBooks() + 1);
            }
            
            $em = $doctrine->getManager();
            $em->flush();

            
            return $this->redirectToRoute("showBooks");
        }
        return $this->render('book/edit.html.twig',['formulaire' => $form->createView()]);
    }


    #[Route('/deleteBook/{id}',name:'deleteBook')]
    public function deleteBook($id,BookRepository $repo, ManagerRegistry $manager){
        $book = $repo->find($id);
        $book->getAuthor()->setNbBooks($book->getAuthor()->getNbBooks() - 1 );
        $em =  $manager ->getManager();
        $em->remove($book);
        $em->flush();
       
        return $this->redirectToRoute("showBooks");
    }

    #[Route('/showbookDetails/{id}',name:'showbookDetails')]
    public function showbookDetails($id,BookRepository $repo){
        $book = $repo->find($id);
        return $this->render('book/showbookDetails.html.twig', ['book' =>$book]);

    }

    #[Route('/deleteAuthorsWithoutBooks',name:'deleteAuthorsWithoutBooks')]
    public function deleteAuthorsWithoutBooks(AuthorRepository $repo, ManagerRegistry $manager){
        $authors = $repo->findBy(['nb_books' => 0]);

        $em =  $manager ->getManager();
        foreach ($authors as $author) {
            $em->remove($author);
        }
        $em->flush();
       
        return $this->redirectToRoute("showAll");

    }


    #[Route('/searchBookByRef',name:'searchBookByRef')]
    public function searchBookByRef(BookRepository $repo,Request $request){
        $ref = $request->query->get('ref');
        if($ref){
            $books = $repo->searchBookByRef($ref);
        }
    
        return $this->render('Book/showBooks.html.twig', ['publishedBooks' => $books ,'ref' => $ref]);
    }

    #[Route('/booksListByAuthors',name:'booksListByAuthors')]
    public function booksListByAuthors(BookRepository $repo){
        $books = $repo->booksListByAuthors();
        
        return $this->render('Book/showBooks.html.twig', ['publishedBooks' => $books]);
    }

    #[Route('/findBooksBefore2023',name:'findBooksBefore2023')]
    public function findBooksBefore2023(BookRepository $repo){
        $books = $repo->findBooksBefore2023();
        
        return $this->render('Book/showBooks.html.twig', ['publishedBooks' => $books]);
    }

    #[Route('/updateScienceFictionToRomance',name:'updateScienceFictionToRomance')]
    public function updateScienceFictionToRomance(BookRepository $repo){
        $nbbook = $repo->updateScienceFictionToRomance();
        return $this->redirectToRoute("showBooks");
    }

    #[Route('/nbLivreRomance',name:'nbLivreRomance')]
    public function nbLivreRomance(BookRepository $repo){
        $nbbooks = $repo->nbLivreRomance();
        
        return $this->render('Book/shownbBooks.html.twig', ['nbBooks' => $nbbooks]);
    }

}
