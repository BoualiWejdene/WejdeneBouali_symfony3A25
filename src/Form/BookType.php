<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Book;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ref')
            ->add('title')
            ->add('publicationDate', DateType::class, [
                'label' => 'Publication Date',
            ])
            ->add('category', ChoiceType::class, [
            'label' => 'Category',
            'choices' => [
                'Science-Fiction' => 'Science-Fiction',
                'Mystery' => 'Mystery',
                'Autobiography' => 'Autobiography',
            ],])
            ->add('author', EntityType::class, [
                'class' => Author::class,
                'choice_label' => 'username',
                'placeholder' => 'Sélectionner un auteur',
                'multiple' => false,
                'expanded' => false,
            ])
       
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
        ]);
    }
}
