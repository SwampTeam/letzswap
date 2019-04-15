<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ItemRepository")
 */
class Item
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="guid")
     */
    private $userId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $conditionStatus;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Picture",
     *     mappedBy="itemId", orphanRemoval=true)
     */
    private $pictures;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ItemStatus",
     *     mappedBy="items", fetch="EXTRA_LAZY")
     */
    private $itemStatuses;

    /**
     * @return mixed
     */
    public function getItemStatuses()
    {
        return $this->itemStatuses;
    }

    /**
     * @param mixed $itemStatuses
     * @return Item
     */
    public function setItemStatuses($itemStatuses)
    {
        $this->itemStatuses = $itemStatuses;
        return $this;
    }

    public function __construct()
    {
        $this->pictures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function setUserId(string $userId): self
    {
        $this->userId = $userId;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getConditionStatus(): ?string
    {
        return $this->conditionStatus;
    }

    public function setConditionStatus(string $conditionStatus): self
    {
        $this->conditionStatus = $conditionStatus;

        return $this;
    }

    /**
     * @return Collection|Picture[]
     */
    public function getPictures(): Collection
    {
        return $this->pictures;
    }

    public function addPicture(Picture $picture): self
    {
        if (!$this->pictures->contains($picture)) {
            $this->pictures[] = $picture;
            $picture->setItemId($this);
        }

        return $this;
    }

    public function removePicture(Picture $picture): self
    {
        if ($this->pictures->contains($picture)) {
            $this->pictures->removeElement($picture);
            // set the owning side to null (unless already changed)
            if ($picture->getItemId() === $this) {
                $picture->setItemId(null);
            }
        }

        return $this;
    }
}
