<?php

namespace App\Entity;

use App\Repository\CachedImageRepository;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;

#[Entity(repositoryClass: CachedImageRepository::class)]
class Cached implements Sizable
{
    #[Id]
    #[GeneratedValue]
    #[Column(type: 'integer')]
    private int $id;

    #[ManyToOne(targetEntity: '\App\Entity\Image', inversedBy: 'caches', )]
    #[JoinColumn(name: 'image_id', referencedColumnName: 'id', nullable: false)]
    private Image $image;

    #[ManyToOne(targetEntity: Dimension::class)]
    #[JoinColumn(name: 'dimension_id', referencedColumnName: 'id')]
    private Dimension $dimension;

    #[Column(type: 'decimal', precision: 9, scale: 5, nullable: true)]
    private ?float $ratio = null;

    #[Column(type: 'string')]
    private string $src;

    #[Column(type: 'integer')]
    private int $width;

    #[Column(type: 'integer')]
    private int $height;

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
     * @return Image
     */
    public function getImage(): Image
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage(Image $image): void
    {
        $this->image = $image;
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
     * @return Dimension
     */
    public function getDimension(): Dimension
    {
        return $this->dimension;
    }

    /**
     * @param Dimension $dimension
     */
    public function setDimension(Dimension $dimension): void
    {
        $this->dimension = $dimension;
    }

    /**
     * @param float|null $ratio
     */
    public function setRatio(?float $ratio): void
    {
        $this->ratio = $ratio;
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
}
