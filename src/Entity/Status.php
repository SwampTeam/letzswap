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
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ItemStatus", mappedBy="status_id")
     */
    private $itemStatuses;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\UserStatus", mappedBy="status_id")
     */
    private $userStatuses;

    public function __construct()
    {
        $this->itemStatuses = new ArrayCollection();
        $this->userStatuses = new ArrayCollection();
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
    public function getItemStatuses(): Collection
    {
        return $this->itemStatuses;
    }

    public function addItemStatus(ItemStatus $itemStatus): self
    {
        if (!$this->itemStatuses->contains($itemStatus)) {
            $this->itemStatuses[] = $itemStatus;
            $itemStatus->addStatusId($this);
        }

        return $this;
    }

    public function removeItemStatus(ItemStatus $itemStatus): self
    {
        if ($this->itemStatuses->contains($itemStatus)) {
            $this->itemStatuses->removeElement($itemStatus);
            $itemStatus->removeStatusId($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserStatus[]
     */
    public function getUserStatuses(): Collection
    {
        return $this->userStatuses;
    }

    public function addUserStatus(UserStatus $userStatus): self
    {
        if (!$this->userStatuses->contains($userStatus)) {
            $this->userStatuses[] = $userStatus;
            $userStatus->addStatusId($this);
        }

        return $this;
    }

    public function removeUserStatus(UserStatus $userStatus): self
    {
        if ($this->userStatuses->contains($userStatus)) {
            $this->userStatuses->removeElement($userStatus);
            $userStatus->removeStatusId($this);
        }

        return $this;
    }
}
