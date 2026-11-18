<?php

include_once(__DIR__ . '/../animals/terrestrial/tiger.php');
include_once(__DIR__ . '/../animals/terrestrial/bear.php');
include_once(__DIR__ . '/../animals/marine/fish.php');
include_once(__DIR__ . '/../animals/aerial/eagle.php');

class AnimalMapper
{
    public function hydrate(array $data): Animal
    {
        $id = (int) $data['id'];
        $weight = (float) $data['weight'];
        $height = (float) $data['height'];
        $age = (int) $data['age'];

        switch ($data['species']) {
            case 'tiger':
                $animal = new Tiger($id, $weight, $height, $age);
                break;
            case 'bear':
                $animal = new Bear($id, $weight, $height, $age);
                break;
            case 'fish':
                $animal = new Fish($id, $weight, $height, $age);
                break;
            case 'eagle':
                $animal = new Eagle($id, $weight, $height, $age);
                break;
            default:
                throw new InvalidArgumentException('Espèce inconnue : ' . $data['species']);
        }

        if (isset($data['enclosure_id'])) {
            $animal->setEnclosureId((int) $data['enclosure_id']);
        }

        $animal->setIsHungry((bool) ($data['is_hungry'] ?? false));
        $animal->setIsSleeping((bool) ($data['is_sleeping'] ?? false));
        $animal->setIsSick((bool) ($data['is_sick'] ?? false));

        return $animal;
    }
}
