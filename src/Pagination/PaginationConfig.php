<?php

namespace App\Pagination;

class PaginationConfig
{
    private int $currentPage;
    private int $itemsPerPage;

    public function __construct(
        int $currentPage,
        int $itemsPerPage
    ) {
        $this->currentPage = $currentPage;
        $this->itemsPerPage = $itemsPerPage;
    }

    public function getCurrentPage(): int {
        return $this->currentPage;
    }

    public function getItemsPerPage(): int {
        return $this->itemsPerPage;
    }

    public function getOffset(): int {
        return $this->getItemsPerPage() * ($this->getCurrentPage() - 1);
    }
}