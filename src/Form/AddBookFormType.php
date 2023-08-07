<?php

namespace App\Form;

use App\Entity\Book;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Unique;

class AddBookFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Author', null, ['attr'=>['placeholder'=>'AuthorName'],  'constraints' => [
                new NotBlank(['message' => 'Please provide the  Author of the book.']),
                new Regex(['pattern'=>'/^[A-Za-z\s]*$/','message'=>'Plz write Author name in letters only.'])

            ]])
            ->add('bookName',null, ['attr'=>['placeholder'=>'BookName'], 'constraints' => [
                new NotBlank(['message' => 'Please provide the  Name of the book.']),
                new Regex(['pattern'=>'/^[A-Za-z\s]*$/','message'=>'Plz write book Name name in letters only.'])
            ]])
            ->add('Quantity', null, ['attr'=>['placeholder'=>'6'], 'constraints' => [
                new NotBlank(['message' => 'Please provide the Quantity of the book.']),
                new Positive([
                    'message'=>'Plz write Quantity name in pos digits only.'])
            ]])
            ->add('isbn', TextareaType::class, ['attr'=>['placeholder'=>'2letters+3digits'],
                'constraints' => [
                    new NotBlank(['message' => 'Please provide the Isbn of the book.']),
                    new Regex([
                        'pattern'=>'/^[A-Za-z]{2}\d{3}$/',
                        'message'=>'Regex pattern as :2 letters + 3 digits sequentially.'])
                   // new Unique(['message'=>"Isbn must be unique"])

                ]])
            ->add('save', SubmitType::class)
        ;


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Book::class,
           /* 'constraints' => [
                new UniqueEntity(fields: ['isbn']),],*/
        ]);


    }
}
