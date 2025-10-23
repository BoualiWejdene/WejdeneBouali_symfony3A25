<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    //    /**
    //     * @return Book[] Returns an array of Book objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Book
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function searchBookByRef(string $ref){
        return $this->createQueryBuilder('b')
            ->where('b.ref LIKE :ref')
            ->andWhere('b.published = 1')
            ->setParameter('ref','%' . $ref. '%')
            ->getQuery()
            ->getResult();
    }

    public function booksListByAuthors(){
        return $this->createQueryBuilder('b')
        ->join('b.author','a')
        ->addSelect('a')
        ->where('b.published = 1')
        ->orderBy('a.username','ASC')
        ->getQuery()
        ->getResult();
    }

    public function findBooksBefore2023(){
        return $this->createQueryBuilder('b')
        ->join('b.author','a')
        ->addSelect('a')
        ->where('b.publicationDate < :date')
        ->andWhere('a.nb_books > :minbooks')
        ->setParameter('date', new \DateTime('2023-01-01'))
        ->setParameter('minbooks',10)
        ->getQuery()
        ->getResult();

    }

    public function updateScienceFictionToRomance(){
        return $this->createQueryBuilder('b')
        ->update()
        ->set('b.category',':newcategory')
        ->where('b.category = :oldcategory')
        ->setParameter('oldcategory','Science-Fiction')
        ->setParameter('newcategory','Romance')
        ->getQuery()
        ->execute();

    }


    public function nbLivreRomance(){
        $entityManager=  $this->getEntityManager();
        $query = $entityManager->createQuery('SELECT COUNT(b.id) FROM App\Entity\Book b WHERE b.category LIKE :condition')->setParameter('condition','Romance');
        return $query->getSingleScalarResult();
    }
}
