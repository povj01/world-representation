<?php
declare(strict_types=1);

namespace App\Entity;

class Organism {
    private int $xPos;
    private int $yPos;
    private int $specie;

    public function __construct(int $xPos, int $yPos, int $specie)
    {
        $this->xPos = $xPos;
        $this->yPos = $yPos;
        $this->specie = $specie;
    }

    public function getXPos(): int
    {
        return $this->xPos;
    }

    public function getYPos(): int
    {
        return $this->yPos;
    }

    public function getSpecie(): int
    {
        return $this->specie;
    }
}
