<?php

namespace InstagramFeed;

use Psr\Cache\CacheItemPoolInterface;

class InstagramCachedFeed implements InstagramFeedInterface
{
    const CACHE_DEFAULT_TTL = 600;

    /**
     * @var CacheItemPoolInterface
     */
    private $cache;

    /**
     * @var InstagramFeed
     */
    private $instagramFeed;

    /**
     * @var int
     */
    private $ttl;

    public function __construct(CacheItemPoolInterface $cache, InstagramFeed $instagramFeed, int $ttl = self::CACHE_DEFAULT_TTL)
    {
        $this->cache = $cache;
        $this->instagramFeed = $instagramFeed;
        $this->ttl = $ttl;
    }

    public function getMedia(int $count = self::MEDIA_COUNT, $maxId = null, $minId = null): array
    {
        $key = sprintf('instagram_feed_%s_%s_%s', $count, $maxId, $minId);
        $cacheItem = $this->cache->getItem($key);

        if ($cacheItem->isHit()) {
            return $cacheItem->get();
        }

        $feed = $this->instagramFeed->getMedia($count, $maxId, $minId);

        $cacheItem->set($feed);
        $cacheItem->expiresAfter($this->ttl);

        $this->cache->save($cacheItem);

        return $feed;
    }
}