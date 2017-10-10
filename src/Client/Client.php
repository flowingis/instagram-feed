<?php

namespace InstagramFeed\Client;

class Client
{
    const API_URL = 'https://api.instagram.com/v1/users/self/media/recent/';

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $accessToken;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $client
     * @param string             $accessToken
     */
    public function __construct(\GuzzleHttp\Client $client, string $accessToken)
    {
        $this->client = $client;
        $this->accessToken = $accessToken;
    }

    /**
     * @param int  $count Count of media to return
     * @param null $maxId Return media earlier than this max_id
     * @param null $minId Return media later than this min_id
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    public function get(int $count, $maxId = null, $minId = null)
    {
        return $this->client->get(sprintf('%s?%s', self::API_URL, http_build_query([
            'access_token' => $this->accessToken,
            'count'        => $count,
            'max_id'       => $maxId,
            'min_id'       => $minId,
        ])));
    }
}