<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Models\IncomeCategories;
use \App\Models\ExpenseCategories;
use \App\Models\PaymentMethods;

class Signup extends \Core\Controller
{

    protected function before()
    {
    }

	public function newAction()
    {
      View::renderTemplate('Signup/new.html');
    }
	
	public function createAction()
    {
        $user = new User($_POST);

        if ($user->save())
		{
			$userId=$user->getIdByLogin($user->login);
			$incomeCategories = new IncomeCategories($userId);
			$expenseCategories = new ExpenseCategories($userId);
			$paymentMethods = new PaymentMethods($userId);
			$incomeCategories->save();
			$expenseCategories->save();
			$paymentMethods->save();
			View::renderTemplate('Signup/success.html',[
                'user' => $user
            ]);
        }
		else
		{
            View::renderTemplate('Signup/new.html', [
                'user' => $user
            ]);
        }
    }
	
	public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }
	
	public function redirectMainAction()
	{
		$this->redirect('');
	}
}
