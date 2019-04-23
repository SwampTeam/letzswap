<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserStatusRepository")
 */
class UserStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="statuses")
     * @ORM\JoinColumn(nullable=false)
     *
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="userStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $statuses;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    public function __construct()
    {
        $this->time = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUser(User $userId): self
    {
        $this->users = $userId;
        return $this;
    }

    /**
     * @return Status
     */
    public function getStatuses(): Status
    {
        return $this->statuses;
    }

    public function setStatus(Status $statusId): self
    {
        $this->statuses = $statusId;
        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }
}
