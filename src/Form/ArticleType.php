<?php

namespace App\Form;

use App\Entity\Article;
use Eckinox\TinymceBundle\Form\Type\TinymceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => true,
                'label' => 'Titre de l\'article :',
            ])
            ->add('cover_img', FileType::class, [
                'required' => true,
                'label' => 'Image de couverture :',
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                    'mimeTypes' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                        'image/svg+xml',
                        'image/webp',
                    ],
                    'mimeTypesMessage' => 'Formats acceptés: jpg, png, svg, webp uniquement !',
                    ]), 
                ],
            ])
            ->add('content', TextareaType::class, [
                'required' => true,
                'label' => 'Rédiger un article :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'La rédaction d\'un article est obligatoire !',
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'L\'article doit comprendre 3 caractères au minmum !,'
                    ]),
                ],
            ])
            ->add('Publier', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
        ]);
    }
}
