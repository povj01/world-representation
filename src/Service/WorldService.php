<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Organism;
use App\Entity\World;

/**
 * Class WorldService
 * @package App\Service
 */
class WorldService
{
    /**
     * Create full world and add organisms.
     * @param int $dimensionX
     * @param int $dimensionY
     * @param int $species
     * @param int $iterations
     * @return World
     */
    public function createWorld(int $dimensionX, int $dimensionY, int $species, int $iterations): World
    {
        $organisms = [];
        $world = new World($dimensionX, $dimensionY, $species, $iterations);
        $matrix = $this->createMatrix($world);
        $matrixWithOrganisms = $this->addOrganisms($matrix, $world);

        foreach ($matrixWithOrganisms as $xKey => $xValue) {
            foreach ($xValue as $yKey => $specie) {
                if ($specie !== null) {
                    $organisms[] = new Organism($xKey, $yKey, $specie);
                }
            }
        }

        $world->addOrganisms($organisms);

        return $world;
    }

    /**
     * Add organisms to m:n matrix.
     * @param array $matrix
     * @param World $world
     * @return array
     */
    private function addOrganisms(array $matrix, World $world): array
    {
        for ($i = 1; $i <= $world->getIterations(); $i++) {
            $newOrganismType = $this->generateRandomSpecie($world->getSpecies());
            $xPos = rand(0, $world->getDimensionX() - 1);
            $yPos = rand(0, $world->getDimensionY() - 1);

            $isToBirth = $this->isSameTypesAround($xPos, $yPos, $newOrganismType, $matrix);

            if ($isToBirth) {
                $matrix[$xPos][$yPos] = $newOrganismType;
            }
        }

        return $matrix;
    }

    /**
     * Check if some same type is surround of concrete item.
     * Check rules that set for Organism births.
     * @param int $xPos
     * @param int $yPos
     * @param int $newOrganismType
     * @param array $matrix
     * @return bool
     */
    private function isSameTypesAround(int $xPos, int $yPos, int $newOrganismType, array $matrix): bool
    {
        $sameTypesCount = 0;
        $surroundsArray = [
            $matrix[$xPos - 1][$yPos],
            $matrix[$xPos + 1][$yPos],
            $matrix[$xPos][$yPos - 1],
            $matrix[$xPos][$yPos + 1],
            $matrix[$xPos + 1][$yPos + 1],
            $matrix[$xPos - 1][$yPos - 1],
            $matrix[$xPos + 1][$yPos - 1],
            $matrix[$xPos - 1][$yPos + 1],
        ];

        if ($this->containsOnlyNull($surroundsArray)) {
            return true;
        }

        foreach ($surroundsArray as $organismType) {
            if ($organismType === $newOrganismType) {
                $sameTypesCount ++;
            }
        }

        // 4 or more types surround, overcrowding = die
        if ($sameTypesCount > 3) {
            return false;
        }

        // 1 or 0 same types surround, isolation = die
        if ($sameTypesCount < 2) {
            return false;
        }

        // 2 or 3 same types surround = live
        if ($sameTypesCount === 3 || $sameTypesCount === 2) {
            return true;
        }

        return true;
    }

    /**
     * Check if surround items are only null.
     * @param array $array
     * @return bool
     */
    private function containsOnlyNull(array $array): bool
    {
        foreach ($array as $val) {
            if (!is_null($val)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Initialize m:n matrix.
     * @param World $world
     * @return array
     */
    private function createMatrix(World $world): array
    {
        $matrix = [];

        $vector1 = range(0, $world->getDimensionX() - 1);
        $vector2 = range(0, $world->getDimensionY() - 1);

        foreach ($vector1 as $x => $value) {
            foreach ($vector2 as $y) {
                $matrix[$x][$y] = null;
            }
        }

        return $matrix;
    }

    /**
     * Generate random species of max of species enter.
     * @param int $maxSpecies
     * @return int
     */
    private function generateRandomSpecie(int $maxSpecies): int
    {
        return rand(1, $maxSpecies);
    }

    /**
     * Debugging table HTML -> shows full of matrix.
     * @param array $matrix
     */
    private function show(array $matrix): void
    {
        echo '<table border=1 cellspacing=0 cellpadding=5 style="float: left; margin-right:20px;">';

        foreach ($matrix as $m) {
            echo '<tr>';

            foreach ($m as $n) {
                echo '<td>'.$n.'</td>';
            }

            echo '</tr>';
        }

        echo '</table>';
    }
}
