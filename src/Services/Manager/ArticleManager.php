<?php

namespace App\Services\Manager;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ArticleManager
 * @package App\Services\Manager
 */
class ArticleManager
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ArticleManager constructor.
     * @param TokenStorageInterface $tokenStorage
     * @param ArticleRepository $repository
     * @param EntityManagerInterface $em
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        ArticleRepository $repository,
        EntityManagerInterface $em
    )
    {
        $this->tokenStorage = $tokenStorage;
        $this->repository = $repository;
        $this->em = $em;
    }

    /**
     * @param Article $article
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function create(Article $article): void
    {
        $article->setAuthor($this->tokenStorage->getToken()->getUser());
        $article->setCreatedAt();
        $this->repository->saveNewArticle($article);
    }

    /**
     * @param Article $article
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function edit(Article $article): void
    {
        $this->repository->saveExistingArticle();
    }

    /**
     * @param Article $article
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $article): void
    {
        $this->repository->remove($article);
    }
}
