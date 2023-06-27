<?php

namespace App\Entity;

use App\Repository\DimensionRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity(repositoryClass: DimensionRepository::class)]
class Dimension implements Sizable
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private ?int $id = null;

    #[Column(type: 'string')]
    private string $name;

    #[Column(type: 'integer')]
    private int $width;

    #[Column(type: 'integer')]
    private int $height;

    #[Column(type: 'decimal', precision: 9, scale: 5, nullable: true)]
    private ?float $ratio = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
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
}