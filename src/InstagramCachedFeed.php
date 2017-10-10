<?php

namespace InstagramFeed;

use InstagramFeed\Client\Client;
use InstagramFeed\Exception\InstagramFeedException;
use InstagramFeed\Model\Media;

class InstagramFeed
{
    const MEDIA_COUNT = 5;

    /**
     * @var Client
     */
    private $client;

    /**
     * InstagramFeed constructor.
     *
     * @param \GuzzleHttp\Client $client
     * @param string             $accessToken
     */
    public function __construct(\GuzzleHttp\Client $client, string $accessToken)
    {
        $this->client = new Client($client, $accessToken);
    }

    /**
     * @param int  $count Count of media to return
     * @param null $maxId Return media earlier than this max_id
     * @param null $minId Return media later than this min_id
     *
     * @return Media[]
     * @throws InstagramFeedException
     */
    public function getMedia(int $count = self::MEDIA_COUNT, $maxId = null, $minId = null)
    {
        try {
            $body = \GuzzleHttp\json_decode(
                $this->client
                    ->get($count, $maxId, $minId)
                    ->getBody(),
                true
            );

            if (!isset($body['data']) || empty($body['data'])) {
                return [];
            }

            $media = [];
            foreach ($body['data'] as $data) {
                $media[] = Media::create($data);
            }

            return $media;
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            throw new InstagramFeedException($e->getMessage());
        } catch (\InvalidArgumentException $e) {
            throw new InstagramFeedException($e->getMessage());
        }
    }
}