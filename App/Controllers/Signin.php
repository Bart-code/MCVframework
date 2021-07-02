<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Signin extends \Core\Controller
{

    protected function before()
    {
        
    }
	
	public function loginAction()
    {
        $user = new User($_POST);

        if ($user  && $user->authenticate( $user->login, $user->password )) {
            $this->redirect('/signin/success');
        } 
		else
		{
            View::renderTemplate('Home/index.html', [ 'user' => $user]);
        }
    }
	
	public function successAction()
    {
        View::renderTemplate('MainSite/success.html');
    }
}
