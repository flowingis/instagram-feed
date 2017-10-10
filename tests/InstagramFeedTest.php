<?php


use InstagramFeed\InstagramFeed;
use PHPUnit\Framework\TestCase;

class InstagramFeedTest extends TestCase
{
    /**
     * @test
     * @expectedException \InstagramFeed\Exception\InstagramFeedException
     */
    public function should_throw_an_exception_on_server_error_request()
    {
        $guzzleClient = $this->prophesize(\GuzzleHttp\Client::class);
        $guzzleClient
            ->get('https://api.instagram.com/v1/users/self/media/recent/?access_token=token&count=1&max_id=2&min_id=3')
            ->willThrow(new \GuzzleHttp\Exception\BadResponseException('bad response', new \GuzzleHttp\Psr7\Request('get', 'url')));

        $connection = new InstagramFeed($guzzleClient->reveal(), 'token');
        $connection->getMedia(1, 2, 3);
    }

    /**
     * @test
     * @expectedException \InstagramFeed\Exception\InstagramFeedException
     */
    public function should_throw_an_exception_on_invalid_json_response()
    {
        $response = new \GuzzleHttp\Psr7\Response(
            200,
            [],
            'invalid json'
        );

        $guzzleClient = $this->prophesize(\GuzzleHttp\Client::class);
        $guzzleClient
            ->get('https://api.instagram.com/v1/users/self/media/recent/?access_token=token&count=1')
            ->willReturn($response);

        $client = $this->prophesize(\InstagramFeed\Client\Client::class);
        $client->get(1)
            ->willReturn($response);

        $connection = new InstagramFeed($guzzleClient->reveal(), 'token');
        $connection->getMedia(1);
    }

    /**
     * @test
     */
    public function should_return_media_items()
    {
        $response = new \GuzzleHttp\Psr7\Response(
            200,
            [],
            file_get_contents(__DIR__ . '/fixtures/instagram_feed.json')
        );

        $guzzleClient = $this->prophesize(\GuzzleHttp\Client::class);
        $guzzleClient
            ->get('https://api.instagram.com/v1/users/self/media/recent/?access_token=token&count=1')
            ->willReturn($response);

        $client = $this->prophesize(\InstagramFeed\Client\Client::class);
        $client->get(1)
            ->willReturn($response);

        $connection = new InstagramFeed($guzzleClient->reveal(), 'token');

        $media = $connection->getMedia(1);
        $this->assertCount(1, $media);

        $post = array_shift($media);
        $this->assertEquals('image', $post->getType());

        $this->assertEquals('1600159965855067792_17941186', $post->getId());
        $this->assertEquals('2017-09-09', $post->getCreatedAt()->format('Y-m-d'));
        $this->assertEquals('Il #country cambia nome in #retreat ma non cambia la bellezza delle location. #ideato #lifeinideato #extrategy', $post->getCaption());
        $this->assertEquals(10, $post->getLikes());
        $this->assertEquals(['ideato', 'extrategy', 'lifeinideato', 'country', 'retreat'], $post->getTags());
        $this->assertEquals('Skyline', $post->getFilter());
        $this->assertEquals('image', $post->getType());
        $this->assertEquals('https://www.instagram.com/p/BY06QLMl66Q/', $post->getLink());
        $this->assertEquals('Simone  D\'Amico', $post->getUser()->getFullName());
        $this->assertEquals('https://scontent.cdninstagram.com/t51.2885-15/s640x640/sh0.08/e35/21480351_1894852077433688_505598409009266688_n.jpg', $post->getImage()->getStandard());
        $this->assertEquals('Borgoumbro', $post->getLocation()->getName());
    }
}
