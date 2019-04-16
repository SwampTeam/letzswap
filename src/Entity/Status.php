<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\StatusRepository")
 */
class Status
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $label;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ItemStatus", mappedBy="statuses")
     */
    private $itemStatus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserStatus", mappedBy="statuses")
     */
    private $userStatus;

    public function __construct()
    {
        $this->itemStatus = new ArrayCollection();
        $this->userStatus = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection|ItemStatus[]
     */
    public function getItemStatus(): Collection
    {
        return $this->itemStatus;
    }

    public function addItemStatus(ItemStatus $itemStatus): self
    {
        if (!$this->itemStatus->contains($itemStatus)) {
            $this->itemStatus[] = $itemStatus;
            $itemStatus->addStatusId($this);
        }

        return $this;
    }

    public function removeItemStatus(ItemStatus $itemStatus): self
    {
        if ($this->itemStatus->contains($itemStatus)) {
            $this->itemStatus->removeElement($itemStatus);
            $itemStatus->removeStatusId($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserStatus[]
     */
    public function getUserStatus(): Collection
    {
        return $this->userStatus;
    }

    public function addUserStatus(UserStatus $userStatus): self
    {
        if (!$this->userStatus->contains($userStatus)) {
            $this->userStatus[] = $userStatus;
            $userStatus->addStatusId($this);
        }

        return $this;
    }

    public function removeUserStatus(UserStatus $userStatus): self
    {
        if ($this->userStatus->contains($userStatus)) {
            $this->userStatus->removeElement($userStatus);
            $userStatus->removeStatusId($this);
        }

        return $this;
    }
}
