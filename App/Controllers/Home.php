<?php

namespace App\Controllers;

use \Core\View;

class Home extends \Core\Controller
{

    protected function before()
    {
		session_start();
		if( isset( $_SESSION['loggedUserId'] ))
		{
			 $this->redirect('/signin/success');
			exit();
		}
    }

    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }
}
