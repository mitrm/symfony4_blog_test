<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{

    /**
     * ArticleRepository constructor.
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * List all object with paginator.
     * @param int $page
     * @param int $maxResults
     * @return Paginator
     */
    public function paginator(int $page, int $maxResults): Paginator
    {
        $qb = $this->createQueryBuilder('p');

        $qb->setFirstResult(($page - 1) * $maxResults)
            ->setMaxResults($maxResults)
            ->orderBy('p.createdAt', 'DESC');

        return new Paginator($qb);
    }

    /**
     * @return string
     * @throws NonUniqueResultException
     */
    public function countArticles(): string
    {
        return $this->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param Article $article
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveNewArticle(Article $article): void
    {
        $this->_em->persist($article);
        $this->_em->flush();
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function saveExistingArticle(): void
    {
        $this->_em->flush();
    }

    /**
     * @param Article $article
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Article $article): void
    {
        $this->_em->remove($article);
        $this->_em->flush();
    }
}
