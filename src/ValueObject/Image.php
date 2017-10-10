<?php

namespace InstagramFeed\ValueObject;

class Image implements \JsonSerializable
{
    private $low;
    private $thumbnail;
    private $standard;

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getLow()
    {
        return $this->low;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @return string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    public function jsonSerialize()
    {
        return [
            'low'       => $this->low,
            'thumbnail' => $this->thumbnail,
            'standard'  => $this->standard,
        ];
    }

    /**
     * @param array $data
     *
     * @return Image
     */
    public static function create(array $data): Image
    {
        $image = new self();
        $image->low = isset($data['low_resolution']) ? $data['low_resolution']['url'] : null;
        $image->thumbnail = isset($data['thumbnail']) ? $data['thumbnail']['url'] : null;
        $image->standard = isset($data['standard_resolution']) ? $data['standard_resolution']['url'] : null;

        return $image;
    }
}