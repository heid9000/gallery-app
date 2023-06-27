<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: ImageRepository::class)]
class Image implements Sizable
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'integer')]
    private int $width;

    #[Column(type: 'integer')]
    private int $height;

    #[Column(type: 'decimal', precision: 9, scale: 5, nullable: true)]
    private ?float $ratio = null;

    #[Column(type: 'string')]
    private string $src;

    #[Column(type: 'string')]
    private string $type;

    #[Column(type: 'string')]
    private string $imageHash;

    #[Column(type: 'string', nullable: true)]
    private ?string $error;

    #[OneToMany(mappedBy: 'image', targetEntity: Cached::class)]
    private Collection $caches;

    public function __construct()
    {
        $this->caches = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * @param int $width
     */
    public function setWidth(int $width): void
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @param int $height
     */
    public function setHeight(int $height): void
    {
        $this->height = $height;
    }

    /**
     * @return float|null
     */
    public function getRatio(): ?float
    {
        if (! isset($this->ratio)) {
            $this->ratio = round($this->getWidth() / $this->getHeight(), 5);
        }
        return $this->ratio;
    }

    /**
     * @param float|null $ratio
     */
    public function setRatio(?float $ratio): void
    {
        $this->ratio = $ratio;
    }

    /**
     * @return string
     */
    public function getSrc(): string
    {
        return $this->src;
    }

    /**
     * @param string $src
     */
    public function setSrc(string $src): void
    {
        $this->src = $src;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getImageHash(): string
    {
        return $this->imageHash;
    }

    /**
     * @param string $imageHash
     */
    public function setImageHash(string $imageHash): void
    {
        $this->imageHash = $imageHash;
    }

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     */
    public function setError(?string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return Collection
     */
    public function getCaches(): Collection
    {
        return $this->caches;
    }

    /**
     * @param Collection $caches
     */
    public function setCaches(Collection $caches): void
    {
        $this->caches = $caches;
    }
}