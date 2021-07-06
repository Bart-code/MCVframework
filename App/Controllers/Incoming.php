<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;

class Incoming extends \Core\Controller
{
    protected function before()
    {
        
    }
	
	public function getItemsAction()
    {
      
    }

	public function newAction()
    {
      View::renderTemplate('Incoming/new.html');
    }
	
	public function addAction()
    {
        $income= new Income($_POST);
    }
	
	public function successAction()
    {
        //View::renderTemplate('Signup/success.html');
    }

}
