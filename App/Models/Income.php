<?php

namespace App\Models;

use PDO;
use \Core\View;

class Income extends \Core\Model
{
    public $ammountError = "";

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function save()
    {
        $this->validate();

        if(empty($this->ammountError))
		{
      
        }

        return false;
    }
	
    public function validate()
    {

    }

}
