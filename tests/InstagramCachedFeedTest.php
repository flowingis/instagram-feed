<?php

use PHPUnit\Framework\TestCase;

class InstagramCachedFeedTest extends TestCase
{
    private $cacheDirectory;
    private $media = [
        'id'           => 'id',
        'created_time' => 1,
        'likes'        => ['count' => 0],
        'tags'         => ['tag'],
        'filter'       => 'filter',
        'type'         => 'image',
        'link'         => 'http://',
        'user'         => [
            "id"              => "1",
            "full_name"       => "User",
            "profile_picture" => "http://",
            "username"        => "username",
        ],
        'images'       => [],
    ];

    public function setUp()
    {
        $this->cacheDirectory = sys_get_temp_dir() . '/cache/';

        shell_exec('rm -rf ' . $this->cacheDirectory);
    }

    /**
     * @test
     */
    public function should_return_not_cached_media()
    {
        $cacheAdapter = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('', 0, $this->cacheDirectory);
        $instagramFeed = $this->prophesize(InstagramFeed\InstagramFeed::class);

        $connection = new \InstagramFeed\InstagramCachedFeed(
            $cacheAdapter,
            $instagramFeed->reveal(),
            60
        );

        $instagramFeed->getMedia(1, 2, 3)
            ->shouldBeCalled();

        $connection->getMedia(1, 2, 3);
    }

    /**
     * @test
     */
    public function should_return_cached_media()
    {
        $cacheAdapter = new \Symfony\Component\Cache\Adapter\FilesystemAdapter('', 0, $this->cacheDirectory);
        $instagramFeed = $this->prophesize(InstagramFeed\InstagramFeed::class);

        $cacheItem = $cacheAdapter->getItem('instagram_feed_1_2_3');
        $cacheItem->set([\InstagramFeed\Model\Media::create($this->media)]);
        $cacheItem->expiresAfter(10);
        $cacheAdapter->save($cacheItem);

        $connection = new \InstagramFeed\InstagramCachedFeed(
            $cacheAdapter,
            $instagramFeed->reveal(),
            60
        );

        $instagramFeed->getMedia(1, 2, 3)
            ->shouldNotBeCalled();

        $media = $connection->getMedia(1, 2, 3);
        $post = array_shift($media);
        $this->assertEquals('image', $post->getType());
        $this->assertEquals('id', $post->getId());

    }
}
