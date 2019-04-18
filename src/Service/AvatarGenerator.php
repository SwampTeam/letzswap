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

    public function testFunction()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);

        return $messages[$index];
    }
}