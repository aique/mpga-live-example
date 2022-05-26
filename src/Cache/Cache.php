<?php

namespace App\Cache;

use App\Pagination\Paginator;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\TagAwareAdapter;

class Cache
{
    const DEFAULT_TIMEOUT = 21600; // 6 horas

    private TagAwareAdapter $client;

    public function __construct()
    {
        $redisAdapter = new RedisAdapter(
            RedisAdapter::createConnection(
                'redis://redis'
            )
        );

        $this->client = new TagAwareAdapter(
            $redisAdapter
        );
    }

    public function getPaginatedCategories(Paginator $paginator, callable $callback): string {
        return $this->client->get(
            $this->paginatedCategoriesKey($paginator), $callback
        );
    }

    public function getCategory(int $id, callable $callback): string {
        return $this->client->get(
            $this->categoryKey($id), $callback
        );
    }

    public function getNumCategories(callable $callback): int {
        return $this->client->get(
            $this->numCategoriesKey(), $callback
        );
    }

    public function clearPaginatedCategories(): void {
        $this->client->clear('categories_list');
    }

    public function clearCategory(int $id): void {
        $this->client->clear(
            $this->categoryKey($id)
        );
    }

    public function clearNumCategories(): void {
        $this->client->clear(
            $this->numCategoriesKey()
        );
    }

    private function paginatedCategoriesKey(Paginator $paginator): string {
        $paginationConfig = $paginator->getPaginationConfig();

        return sprintf(
            'categories_list_%s_items_per_page_%s_current_page',
            $paginationConfig->getItemsPerPage(),
            $paginationConfig->getCurrentPage()
        );
    }

    private function categoryKey(int $id): string {
        return sprintf(
            'category_detail_%s', $id
        );
    }

    private function numCategoriesKey(): string {
        return 'categories_total_num';
    }
}