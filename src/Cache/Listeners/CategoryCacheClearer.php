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

    public function clearCategoryFromCache(Category $category, LifecycleEventArgs $event): void {
        $this->cache->clearCategory(
            $category->getId()
        );

        $this->cache->clearPaginatedCategories();
    }
}