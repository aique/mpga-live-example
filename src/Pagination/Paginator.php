<?php

namespace App\Pagination;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class Paginator
{
    private PaginationConfig $paginationConfig;
    private ServiceEntityRepository $repository;

    public function __construct(
        PaginationConfig $paginationConfig,
        ServiceEntityRepository $repository
    ) {
        $this->paginationConfig = $paginationConfig;
        $this->repository = $repository;
    }

    public function getPaginationConfig(): PaginationConfig {
        return $this->paginationConfig;
    }

    public function paginate(): array {
        $offset = $this->paginationConfig->getOffset();
        $itemsPerPage = $this->paginationConfig->getItemsPerPage();

        return $this->repository->findBy(
            [], null, $itemsPerPage, $offset
        );
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
        return count($this->repository->findAll());
    }

    private function getNumPages(): int {
        return ceil(
            $this->getNumItems() / $this->paginationConfig->getItemsPerPage()
        );
    }
}