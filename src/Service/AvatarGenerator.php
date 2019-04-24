<?php

namespace App\Service;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class AvatarGenerator
 * @package App\Service
 */
class AvatarGenerator extends ServiceEntityRepository
{

    /**
     * AvatarGenerator constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(User::class);
    }

    /**
     * @param string $username
     * @param int $size
     * @return string
     */
    public function getAvatar(string $username, int $size): string
    {
        $email = $this->repository->findOneByUsername($username)->getEmail();
        $grav_url = '<img src="https://www.gravatar.com/avatar/' . md5(strtolower(trim($email))) . "?r=g" . "&s=" . $size . '" alt="' . $username . ' logo">';
        return $grav_url;
    }

}