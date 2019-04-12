<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemStatusRepository")
 */
class ItemStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Item")
     */
    private $item_id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Status", inversedBy="itemStatuses")
     */
    private $status_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $time;

    public function __construct()
    {
        $this->item_id = new ArrayCollection();
        $this->status_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItemId(): Collection
    {
        return $this->item_id;
    }

    public function addItemId(Item $itemId): self
    {
        if (!$this->item_id->contains($itemId)) {
            $this->item_id[] = $itemId;
        }

        return $this;
    }

    public function removeItemId(Item $itemId): self
    {
        if ($this->item_id->contains($itemId)) {
            $this->item_id->removeElement($itemId);
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
