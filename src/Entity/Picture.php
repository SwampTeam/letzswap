<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PictureRepository")
 */
class Picture
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
    private $mimeType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Item", inversedBy="pictures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $itemId;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getItemId(): ?Item
    {
        return $this->itemId;
    }

    public function setItemId(?Item $itemId): self
    {
        $this->itemId = $itemId;

        return $this;
    }
}
