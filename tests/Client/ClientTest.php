<?php

use InstagramFeed\Client\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    /**
     * @test
     */
    public function should_perform_a_valid_call()
    {
        $guzzleClient = $this->prophesize(\GuzzleHttp\Client::class);

        $client = new Client($guzzleClient->reveal(), 'token');

        $guzzleClient
            ->get('https://api.instagram.com/v1/users/self/media/recent/?access_token=token&count=1&max_id=2&min_id=3')
            ->shouldBeCalled();

        $client->get(1, 2, 3);
    }
}
