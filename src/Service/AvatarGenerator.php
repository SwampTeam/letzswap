<?php


namespace App\Service;


use App\Entity\User;

class AvatarGenerator
{
    /**
     * @param User $user
     * @param int $size
     * @return string
     */
    public function getAvatar(string $user, int $size): string
    {
//        $email = new User();
//        $email->getEmail($user);
        $grav_url = "https://www.gravatar.com/avatar/" . md5(strtolower(trim($user))) . "?d=" . "&s=" . $size;

        // return "<img src='" . $grav_url . "'>";
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