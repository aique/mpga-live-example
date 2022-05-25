<?php

namespace App\Pagination;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginatorBuilder
{
    private EntityManagerInterface $entityManager;
    private PaginationConfigBuilder $configBuilder;

    public function __construct(
        EntityManagerInterface $entityManager,
        PaginationConfigBuilder $configBuilder
    ) {
        $this->entityManager = $entityManager;
        $this->configBuilder = $configBuilder;
    }

    public function build(string $className, Request $request): Paginator {
        $repository = $this->entityManager->getRepository($className);
        $paginationConfig = $this->configBuilder->build($request);

        return new Paginator(
            $paginationConfig, $repository
        );
    }
}