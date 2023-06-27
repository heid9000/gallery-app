<?php

namespace App\Entity;

interface Sizable
{

    /**
     * @return int
     */
    public function getWidth(): int;

    /**
     * @param int $width
     */
    public function setWidth(int $width): void;

    /**
     * @return int
     */
    public function getHeight(): int;

    /**
     * @param int $height
     */
    public function setHeight(int $height): void;

    /**
     * @return float|null
     */
    public function getRatio(): ?float;

    /**
     * @param float|null $ratio
     */
    public function setRatio(?float $ratio): void;
}