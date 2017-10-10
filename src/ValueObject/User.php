<?php

namespace InstagramFeed\ValueObject;

class User implements \JsonSerializable
{
    private $id;
    private $fullName;
    private $userName;
    private $profilePicture;

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
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * @return string
     */
    public function getProfilePicture()
    {
        return $this->profilePicture;
    }

    public function jsonSerialize()
    {
        return [
            'id'             => $this->id,
            'fullName'       => $this->fullName,
            'userName'       => $this->userName,
            'profilePicture' => $this->profilePicture,
        ];
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public static function create(array $data): User
    {
        $user = new self();
        $user->id = isset($data['id']) ? $data['id'] : null;
        $user->fullName = isset($data['full_name']) ? $data['full_name'] : null;
        $user->userName = isset($data['user_name']) ? $data['user_name'] : null;
        $user->profilePicture = isset($data['profile_picture']) ? $data['profile_picture'] : null;

        return $user;
    }
}