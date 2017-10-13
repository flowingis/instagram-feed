# Instagram Feed

[![Build Status](https://travis-ci.org/ideatosrl/instagram-feed.svg?branch=master)](https://travis-ci.org/ideatosrl/instagram-feed)

Retrieve data from Instagram feed


## Requirements
* PHP 7.1

## Installation 

    composer require ideato/instagram-feed

## Usage

InstagramFeed requires a [Guzzle HTTP Client](http://docs.guzzlephp.org/en/stable/) and an Instagram access token.

>[Here](https://elfsight.com/service/generate-instagram-access-token/) you can find a tool for generate your own instagram access token

```php
    $instagramFeed = new InstagramFeed\InstagramFeed(
        new \GuzzleHttp\Client(),
        'your own access token'
    );
    
    $media = $instagramFeed->getMedia(); //will returns array of InstagramFeed\Model\Media objects
```

### Caching

Instagram APIs come out with low rate limit. This means you have to cache responses in order to prevent errors. 
The library provides a [PSR-6](http://www.php-fig.org/psr/psr-6/) cache decorator with allows you to easily cache responses.

```php
    $instagramFeed = new InstagramFeed\InstagramFeed(
        new \GuzzleHttp\Client(),
        'your own access token'
    );
    
    $instagramCachedFeed = new InstagramFeed\InstagramCachedFeed(
        new \Symfony\Component\Cache\Adapter\FilesystemAdapter(),
        $instagramFeed,
        $ttl //defaults to 600 seconds
    );
    
    $media = $instagramCachedFeed->getMedia(); //will returns array of InstagramFeed\Model\Media objects
```

## Symfony Integration

Instagram Feed can be easily configured as a service on your Symfony application.

```yaml
    services.yml
    
    parameters:
      app_instagram_access_token: 'your own token'
    
    services:
        app.instagram_feed:
            class: InstagramFeed\InstagramFeed
            arguments:
              - "@app.guzzle_client"
              - "%app_instagram_access_token"
    
        app.instagram_cached_feed:
            class: InstagramFeed\InstagramCachedFeed
            arguments:
              - "@cache.app"
              - "@app.instagram_feed"
            public: true

```

Then you can retrieve posts with:

```php
    $this->get('app.instagram_cached_feed')->getMedia();
```

## TODO

- [ ] add support to other api endpoints
- [ ] add support to other Instagram types (eg. video, gallery)
