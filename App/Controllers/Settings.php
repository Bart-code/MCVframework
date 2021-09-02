<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\IncomeCategories;
use \App\Models\ExpenseCategories;
use \App\Models\User;

class Settings extends \Core\Controller
{
    protected function before()
    {
        session_start();
		if(  !( isset($_SESSION['loggedUserId'])))
		{
			View::renderTemplate('Home/index.html');
			exit();
		}
    }
	
	public function accountAction()
    {
		$user = new User();
		$user = $user -> findByID($_SESSION['loggedUserId']);
		
		View::renderTemplate('Settings/account.html',[
                'user' => $user
            ]);
    }
	
	public function accountSettingsSaveAction()
	{
		$user = new User($_POST);
		
		if ( $user->update() )
		{
			View::renderTemplate('Settings/account.html', [
                'user' => $user
            ]);
        }
		else
		{
            View::renderTemplate('Settings/account.html', [
                'user' => $user
            ]);
        }
	}
	
	public function incomesAction()
    {
		View::renderTemplate('Settings/incomes.html');
    }
	
	public function expensesAction()
    {
		View::renderTemplate('Settings/expenses.html');
    }
	
	public function paymenthsAction()
    {
		View::renderTemplate('Settings/paymenths.html');
    }
	
}
