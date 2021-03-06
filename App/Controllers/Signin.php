<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

class Signin extends \Core\Controller
{

    protected function before()
    {
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        }
    }
	
	public function loginAction()
    {
        $user = new User($_POST);

        if ($user  && $user->authenticate( $user->login, $user->password )) 
		{
			$_SESSION['loggedUserId']=$user->getIdByLogin($user->login);
            $this->redirect('/signin/success');
        } 
		else
		{
            View::renderTemplate('Home/index.html', [ 'user' => $user]);
        }
    }
	
	public function successAction()
    {
		if( !isset($_SESSION['loggedUserId']))
		{
			View::renderTemplate('Home/index.html');
			exit();
		}
		else
		{
			$user = new User();
			$userName=$user -> getNameById($_SESSION['loggedUserId']);
			View::renderTemplate('MainSite/mainSite.html', ['userName' => $userName]);
			exit();
		}
    }
	
	public function loggoutAction()
	{
		View::renderTemplate('Home/index.html');
		if( isset($_SESSION['loggedUserId']))
		{
			unset($_SESSION['loggedUserId']);
		}
		session_unset();
	}
}
