<?php

namespace App\Pagination;

use Symfony\Component\HttpFoundation\Request;

class PaginationConfigBuilder
{
    const DEFAULT_ITEMS_PER_PAGE = 20;

    public function build(Request $request): PaginationConfig {
        return new PaginationConfig(
            $request->query->get('current') ?: 1,
            self::DEFAULT_ITEMS_PER_PAGE
        );
    }
}