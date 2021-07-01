<?php

namespace App\Controllers;

use \Core\View;

class Signup extends \Core\Controller
{

    protected function before()
    {
        
    }

    
	public function newAction()
    {
      View::renderTemplate('Signup/new.html');
    }
	
	 public function checkAction()
    {
      echo "działa";
    }

}
