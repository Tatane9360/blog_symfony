<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Comment;
use App\Form\CommentType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    // #[Route('/article/read/{id}', name: 'app_article_read')]
        // public function createComment(Request $request, ManagerRegistry $doctrine, ArticleController $article): Response
        // {
        //     $comment = new Comment();

        //     $form = $this->createForm(CommentType::class, $comment);
        //     $form->handleRequest($request);
            
        //     if ($form->isSubmitted() && $form->isValid()) {
        //         // Enregistrez le commentaire en base de données
        //         $entityManager = $doctrine->getManager();
        //         $entityManager->persist($comment);
        //         $entityManager->flush();

        //         // Redirigez l'utilisateur vers la page d'article ou une autre page appropriée
        //         return $this->redirectToRoute('app_article_read');

        //     }

        //     return $this->render('article/read.html.twig', [
        //         'form' => $form->createView(),
        //         'articles' => $article
        //     ]);
        // }   
}
