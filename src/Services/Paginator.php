<?php

namespace App\Services;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class Paginator
 * @package App\Services
 */
class Paginator
{
    /**
     * @var int
     */
    private $itemPerPage = 20;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * Paginator constructor.
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param int $itemPerPage
     */
    public function setItemPerPage(int $itemPerPage): void
    {
        $this->itemPerPage = $itemPerPage;
    }

    /**
     * @param ServiceEntityRepository $repository
     * @param $page
     * @return mixed
     */
    public function getItemList(ServiceEntityRepository $repository, $page)
    {
        return $repository->paginator($page, $this->itemPerPage);
    }

    /**
     * @param $items
     * @return int
     */
    public function countPage($items): int
    {
        return ceil(\count($items) / $this->itemPerPage);
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        $request = $this->requestStack->getCurrentRequest();
        $page = $request->query->get('page');
        if ($page < 1) {
            $page = 1;
        }
        return $page;
    }
}
