<?php

namespace App\Pagination;

use App\Cache\Cache;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class Paginator
{
    private Cache $cache;
    private PaginationConfig $paginationConfig;
    private ServiceEntityRepository $repository;

    public function __construct(
        Cache $cache,
        PaginationConfig $paginationConfig,
        ServiceEntityRepository $repository
    ) {
        $this->cache = $cache;
        $this->paginationConfig = $paginationConfig;
        $this->repository = $repository;
    }

    public function getPaginationConfig(): PaginationConfig {
        return $this->paginationConfig;
    }

    public function paginate(): array {
        $currentPage = $this->paginationConfig->getCurrentPage();

        if ($this->outOfRange($currentPage)) {
            throw new PaginationOutOfRangeException();
        }

        $offset = $this->paginationConfig->getOffset();
        $itemsPerPage = $this->paginationConfig->getItemsPerPage();

        return $this->repository->findBy(
            [], null, $itemsPerPage, $offset
        );
    }

    private function outOfRange(int $currentPage): bool {
        return $currentPage < 1 || $currentPage > $this->getNumPages();
    }

    public function getHeaders(): array {
        return [
            'Pagination-CurrentPage' => $this->paginationConfig->getCurrentPage(),
            'Pagination-NumItems' => $this->getNumItems(),
            'Pagination-NumPages' => $this->getNumPages(),
            'Pagination-ItemsPerPage' => $this->paginationConfig->getItemsPerPage(),
        ];
    }

    private function getNumItems(): int {
        return $this->cache->getNumCategories(function() {
            return count($this->repository->findAll());
        });
    }

    private function getNumPages(): int {
        return ceil(
            $this->getNumItems() / $this->paginationConfig->getItemsPerPage()
        );
    }
}