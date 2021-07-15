<?php 
class Customer extends CustomerCore { 
//Nouveaux paramètres de classe 
public $privilege_code;
 
public function __construct($id = null) { 
        //Définition du nouveau champ privilege_code
        self::$definition['fields']['privilege_code']     = [
            'type' => self::TYPE_STRING,
            'required' => false, 'size' => 255
        ];
        parent::__construct($id);
    }
}