<?php

namespace App\Pagination;

use App\Cache\Cache;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginatorBuilder
{
    private Cache $cache;
    private EntityManagerInterface $entityManager;
    private PaginationConfigBuilder $configBuilder;

    public function __construct(
        Cache $cache,
        EntityManagerInterface $entityManager,
        PaginationConfigBuilder $configBuilder
    ) {
        $this->cache = $cache;
        $this->entityManager = $entityManager;
        $this->configBuilder = $configBuilder;
    }

    public function build(string $className, Request $request): Paginator {
        $repository = $this->entityManager->getRepository($className);
        $paginationConfig = $this->configBuilder->build($request);

        return new Paginator(
            $this->cache, $paginationConfig, $repository
        );
    }
}