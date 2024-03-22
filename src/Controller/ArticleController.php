<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Service\ArticleService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_home_blog')]
    public function index(ArticleService $service, Request $request): Response
    {
        return $this->render('article/index.html.twig', [
            'articles' => $service->get3LastArticles(),
        ]);
    }

    #[Route('/article/explorer', name: 'app_article_explorer')]
    public function explorer(ArticleService $service): Response
    {
        return $this->render('article/explorer.html.twig', [
            'articles' => $service->getAllArticles(),
        ]);
    }

    #[Route('/article/read/{id}', name: 'app_article_read')]
    public function read(ArticleService $service, int $id): Response
    {
        return $this->render('article/read.html.twig', [
            'article' => $service->getOneArticle($id),
        ]);
    }
    #[Route('/admin/read', name: 'app_admin_explorer')]
    public function adminView(ArticleService $service): Response
    {
        return $this->render('admin/read.html.twig', [
            'articles' => $service->getAllArticles(),
        ]);
    }

    #[Route('/admin/delete/{id}', name: 'app_admin_delete')]
    public function deleteArticle(int $id, ArticleService $service): Response
    {
        $service->deleteOneArticle($id);
        return $this->redirectToRoute('app_admin_explorer');
    }

    // #[Route('/article/{id}/comment', name: 'app_article_comment')]
    // public function createComment(Request $request, ManagerRegistry $doctrine, int $id): Response
    // {
    //     $comment = new Comment();
    //     $form = $this->createForm(CommentType::class, $comment);
    //     $form->handleRequest($request);
    
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $doctrine->getManager();
    //         $entityManager->persist($comment);
    //         $entityManager->flush();
    
    //         return $this->redirectToRoute('app_article_read', ['id' => $id]);
    //     }
    
    //     // Récupérer l'article associé au commentaire
    //     $article = $doctrine->getRepository(Article::class)->find($id);
    
    //     return $this->render('article/read.html.twig', [
    //         'forms' => $form->createView(),
    //         'article' => $article,
    //     ]);
    // }
    


    #[Route('/article/add', name: 'app_article_add')]
    public function createArticle(Request $request, SluggerInterface $slugger, ManagerRegistry $doctrine, ArticleService $service): Response
    {
        $article = new Article();

        $session = $request->getSession();
        $username = $session->get('_security.last_username');

        $article->setAuthor($username);

        $form = $this->createForm(ArticleType::class, $article, [
            'method' => 'POST',
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article = $form->getData();

            $photoFile = $form->get('cover_img')->getData();

            if ($photoFile) {
                $originalFileName = pathinfo($photoFile->getClientOriginalName(), PATHINFO_FILENAME);

                $safeFileName = $slugger->slug($originalFileName);

                $newFileName = $safeFileName . '-' . uniqid() . '.' . $photoFile->guessExtension();

                try {
                    $photoFile->move(
                        $this->getParameter('cover_img_dir'),
                        $newFileName
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue: ' . $e->getMessage());
                }

                $article->setCoverImg($newFileName);
            }

            $article->setDate(new \DateTime('now'));

            $entityManager = $doctrine->getManager();
            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'Nouvel article ajouté avec succès.');
            return $this->redirectToRoute('app_article_explorer');
        }

        return $this->render('article/article-writing.html.twig', [
            'form' => $form->createView(),
            'img' => $article->getCoverImg(),
        ]);
    }
}
