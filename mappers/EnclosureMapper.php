<?php

include_once(__DIR__ . '/../enclosure/enclosure.php');
include_once(__DIR__ . '/../enclosure/aviary.php');
include_once(__DIR__ . '/../enclosure/aquarium.php');

class EnclosureMapper
{
    /**
     * @param Animal[] $animals
     */
    public function hydrate(array $data, array $animals = []): Enclosure
    {
        $type = $data['type'] ?? 'Enclosure';

        switch ($type) {
            case 'Aviary':
                $enclosure = new Aviary((float) $data['height'], (string) $data['name']);
                if (!empty($data['top_cleanliness'])) {
                    $enclosure->setTopCleanliness((string) $data['top_cleanliness']);
                }
                break;

            case 'Aquarium':
                $enclosure = new Aquarium((float) $data['salinity'], (string) $data['name']);
                break;

            default:
                $enclosure = new Enclosure((string) $data['name']);
                break;
        }

        if (isset($data['id'])) {
            $enclosure->setId((int) $data['id']);
        }

        if (isset($data['zoo_id'])) {
            $enclosure->setZooId((int) $data['zoo_id']);
        }

        if (isset($data['actual_species'])) {
            $enclosure->setActualSpecies((string) $data['actual_species']);
        }

        if (isset($data['cleanliness'])) {
            $enclosure->setCleanliness((string) $data['cleanliness']);
        }

        $enclosure->setAnimals($animals);

        return $enclosure;
    }
}
