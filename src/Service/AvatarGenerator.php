<?php

namespace App\Service;

class AvatarGenerator
{
    /**
     * @param string $email
     * @param string $username
     * @param int $size
     * @return string
     */
    public function getAvatar(string $email, string $username, int $size): string
    {
        $grav_url = '<img src="https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . "?s=" . $size . '" alt="' . $username . ' logo">';

        return $grav_url;
    }
    
}