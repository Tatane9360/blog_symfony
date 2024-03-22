<?php
namespace App\Service;

use App\Entity\Article;
use Doctrine\Persistence\ManagerRegistry;
use Exception;


class ArticleService
{
    private ManagerRegistry $doctrine;
    public function __construct(ManagerRegistry $doctrine) {
            $this->doctrine = $doctrine;
    }

    /**
     * Récupération de toutes les tâches
     *
     * @return Article[] return an array of Task objects
     */
    public function getAllArticles(): array {
        return $this->doctrine->getManager()->getRepository(Article::class)->findby([],['date' => 'DESC']);
    }

    public function get3LastArticles(): array {
        return $this->doctrine->getManager()->getRepository(Article::class)->findby([],['date' => 'DESC'], 3);
    }

    public function deleteOneArticle(int $id): bool
    {
        $article = $this->doctrine->getManager()->getRepository(Article::class)->findOneBy(['id' => $id]);
        try {
            $this->doctrine->getManager()->remove($article);
            $this->doctrine->getManager()->flush();
        } catch(Exception $e) {
            return false;
        }

        return true;
    }

    public function getOneArticle(int $id)
    {
        $article = $this->doctrine->getManager()->getRepository(Article::class)->findOneBy(['id' => $id]);
        $this->doctrine->getManager()->flush();

        return $article;
    }



    
}