<?php

namespace InstagramFeed\ValueObject;

class GeoLocation implements \JsonSerializable
{
    private $id;
    private $latitude;
    private $longitude;
    private $name;

    private function __construct()
    {
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function jsonSerialize()
    {
        return [
            'id'        => $this->id,
            'latitude'  => $this->latitude,
            'longitude' => $this->longitude,
            'name'      => $this->name,
        ];
    }

    /**
     * @param array $data
     *
     * @return GeoLocation
     */
    public static function create(array $data): GeoLocation
    {
        $geoLocation = new self();

        foreach ($data as $property => $value) {
            if (!property_exists(self::class, $property)) {
                continue;
            }

            $geoLocation->{$property} = $value;
        }

        return $geoLocation;
    }
}
