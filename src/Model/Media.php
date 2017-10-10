<?php

namespace InstagramFeed\Model;

use InstagramFeed\ValueObject\GeoLocation;
use InstagramFeed\ValueObject\Image;
use InstagramFeed\ValueObject\User;

class Media
{
    private $id;
    private $createdAt;
    private $caption;
    private $likes;
    private $tags;
    private $filter;
    private $type;
    private $link;

    /**
     * @var User
     */
    private $user;

    /**
     * @var GeoLocation
     */
    private $location;

    /**
     * @var Image
     */
    private $image;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @return int
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * @return array
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @return string
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return GeoLocation
     */
    public function getLocation(): GeoLocation
    {
        return $this->location;
    }

    /**
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    public static function create($data)
    {
        $media = new self();
        $media->id = $data['id'];
        $media->createdAt = (new \DateTimeImmutable())->setTimestamp($data['created_time']);
        $media->caption = isset($data['caption']) && isset($data['caption']['text']) ? $data['caption']['text'] : '';
        $media->likes = $data['likes']['count'];
        $media->tags = $data['tags'];
        $media->filter = $data['filter'];
        $media->type = $data['type'];
        $media->link = $data['link'];
        $media->user = User::create($data['user']);
        $media->image = Image::create($data['images']);

        if (isset($data['location']) && !empty($data['location'])) {
            $media->location = GeoLocation::create($data['location']);
        }

        return $media;
    }
}