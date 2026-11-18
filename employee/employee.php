<?php 

include_once('./enclosure/enclosure.php');

/**
 * Class employee.php
 *
 * @author Jeremy Michel
 */
class Employee{

    protected ?int $id = null;
    protected string $name;
    protected int $age;
    protected string $gender; //male or female

    /**
     * Employee constructor.
     */
    public function __construct(string $name, int $age, string $gender)
    {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
    }

    // TOSTRING ex :
    public function __toString(): string {
        $string= 'Employee name : ' . $this->getName() . ' <br> ';
        $string.= 'Age : ' . $this->getAge() . ' <br> ';
        $string.= 'Gender : ' . $this->getGender() . ' <br> ';                
        $string.='<br>';
        return $string;     
        
    }


    ////////////
    // GETTER //
    ////////////

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * return employee name
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * return employee age
     * @return int
     */
    public function getAge(): int
    {
        return $this->age;
    }

    /**
     * return employee gender
     * @return string
     */
    public function getGender(): string
    {
        return $this->gender;
    }


    ////////////
    // SETTER //
    ////////////

    /**
     * Set the value of id
     * @return  self
     */
    public function setId(?int $newId): self
    {
        $this->id = $newId;

        return $this;
    }

    /**
     * Set the value of name
     * @return  self
     */ 
    public function setName($newName):self
    {
        $this->name = $newName;

        return $this;
    }

    /**
     * Set the value of age
     * @return  self
     */ 
    public function setAge($newAge):self
    {
        $this->age = $newAge;

        return $this;
    }

    /**
     * Set the value of gender
     * @return  self
     */ 
    public function setGender($newGender):self
    {
        $this->gender = $newGender;

        return $this;
    }

    // displays the animals contained in the enclosure, as well as it's characteristics
    public function checkEnclosure(Enclosure $enclosure)
    {
        echo $enclosure;
        //si il y a des animaux on affiche aussi leurs caractéristiques
        if(count($enclosure->getAnimals())!=0){
            $enclosure->displayAnimalCharacteristics();
        }
    }

    /**
     * cleaning the enclosure if it's empty
     * @return  void
     */ 
    public function cleaning(Enclosure $enclosure):void
    {       
        $enclosure->cleaning();    
    }


    /**
     * feed animals that do not sleep
     * @return bool true if at least one animal was sleeping
     */ 
    public function toFeed(Enclosure $enclosure): bool
    {
        $someoneSleepy = false;
        foreach($enclosure->getAnimals() as $animal){
            if($animal->getIsSleeping() == false){
                $animal->setIsHungry(false);
            }else{
                $someoneSleepy = true;
            }
        }
        return $someoneSleepy;
    }

    /**
     * add animals to an enclosure if it's possible
     * @return  void
     */ 
    public function addToEnclosure(Enclosure $enclosure, Animal $animal):void
    {
        if(count($enclosure->getAnimals()) < $enclosure->getLength()){
            $enclosure->addAnimal($animal);
        } else {
            echo 'the enclosure is already full <br><br>';
        }
    }

    /**
     * remove animals from an enclosure
     * @return  void
     */ 
    public function removeFromEnclosure(Enclosure $enclosure, Animal $animal):void
    {
        $enclosure->removeAnimal($animal);
    }

    /**
     * move animals from an enclosure to another if it's possible
     * @return  void
     */ 
    public function moveAnimal(Enclosure $enclosureFrom, Animal $animal, Enclosure $enclosureTo):void
    {
        $this->removeFromEnclosure($enclosureFrom, $animal);
        $this->addToEnclosure($enclosureTo, $animal);
    }


}

?>