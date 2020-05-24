<?php
declare(strict_types=1);

namespace App\Entity;

class World {
    private int $dimensionX;
    private int $dimensionY;
    private int $species;
    private int $iterations;
    private array $organisms = [];

    public function __construct(
        int $dimensionX,
        int $dimensionY,
        int $species,
        int $iterations
    ) {
        $this->dimensionX = $dimensionX;
        $this->dimensionY = $dimensionY;
        $this->species = $species;
        $this->iterations = $iterations;
    }

    public function getDimensionX(): int
    {
        return $this->dimensionX;
    }

    public function getDimensionY(): int
    {
        return $this->dimensionY;
    }

    public function getSpecies(): int
    {
        return $this->species;
    }

    public function getIterations(): int
    {
        return $this->iterations;
    }

    public function addOrganisms(array $organisms): void
    {
        $this->organisms = $organisms;
    }

    public function getWorldArray(): array
    {
        $organisms = [];

        /** @var Organism $organism */
        foreach ($this->organisms as $organism) {
            $organisms[] = [
                'xPos' => $organism->getXPos(),
                'yPos' => $organism->getYPos(),
                'species' => $organism->getSpecie(),
            ];
        }

        return [
            'dimensionX' => $this->dimensionX,
            'dimensionY' => $this->dimensionY,
            'iterations' => $this->iterations,
            'species' => $this->species,
            'organism' => $organisms,
        ];
    }
}
