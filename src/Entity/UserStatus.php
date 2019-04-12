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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     */
    private $user_id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Status", inversedBy="userStatuses")
     */
    private $status_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    public function __construct()
    {
        $this->user_id = new ArrayCollection();
        $this->status_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUserId(): Collection
    {
        return $this->user_id;
    }

    public function addUserId(User $userId): self
    {
        if (!$this->user_id->contains($userId)) {
            $this->user_id[] = $userId;
        }

        return $this;
    }

    public function removeUserId(User $userId): self
    {
        if ($this->user_id->contains($userId)) {
            $this->user_id->removeElement($userId);
        }

        return $this;
    }

    /**
     * @return Collection|Status[]
     */
    public function getStatusId(): Collection
    {
        return $this->status_id;
    }

    public function addStatusId(Status $statusId): self
    {
        if (!$this->status_id->contains($statusId)) {
            $this->status_id[] = $statusId;
        }

        return $this;
    }

    public function removeStatusId(Status $statusId): self
    {
        if ($this->status_id->contains($statusId)) {
            $this->status_id->removeElement($statusId);
        }

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }
}
