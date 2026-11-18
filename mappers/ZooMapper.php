<?php

include_once(__DIR__ . '/../zoo/zoo.php');

class ZooMapper
{
    /**
     * @param Enclosure[] $enclosures
     */
    public function hydrate(array $data, Employee $employee, array $enclosures = []): Zoo
    {
        $zoo = new Zoo(
            (string) $data['name'],
            $employee,
            (int) $data['max_enclosure']
        );

        if (isset($data['id'])) {
            $zoo->setId((int) $data['id']);
        }

        $zoo->setEnclosures($enclosures);

        return $zoo;
    }
}
