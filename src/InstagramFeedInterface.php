<?php

namespace InstagramFeed;

interface InstagramFeedInterface
{
    const MEDIA_COUNT = 5;

    public function getMedia(int $count = self::MEDIA_COUNT, $maxId = null, $minId = null): array;
}