<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemStatusRepository")
 */
class ItemStatus
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="statuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $items;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Status", inversedBy="itemStatus")
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
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function setItem(Item $itemId): self
    {
        $this->items = $itemId;

        return $this;
    }

    public function removeItem(Item $itemId): self
    {
        if ($this->items->contains($itemId)) {
            $this->items->removeElement($itemId);
        }

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

    public function removeStatus(Status $statusId): self
    {
        if ($this->statuses->contains($statusId)) {
            $this->statuses->removeElement($statusId);
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
