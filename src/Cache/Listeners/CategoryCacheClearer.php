<?php

namespace App\Cache\Listeners;

use App\Cache\Cache;
use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class CategoryCacheClearer
{
    private Cache $cache;

    public function __construct(Cache $cache) {
        $this->cache = $cache;
    }

    public function persistCategoryFromCache(Category $category, LifecycleEventArgs $event): void {
        $this->cache->clearPaginatedCategories();
        $this->cache->clearNumCategories();
    }

    public function updateCategoryFromCache(Category $category, LifecycleEventArgs $event): void {
        $this->cache->clearCategory(
            $category->getId()
        );

        $this->cache->clearPaginatedCategories();
    }

    public function deleteCategoryFromCache(Category $category, LifecycleEventArgs $event): void {
        $this->cache->clearCategory(
            $category->getId()
        );

        $this->cache->clearPaginatedCategories();
        $this->cache->clearNumCategories();
    }
}