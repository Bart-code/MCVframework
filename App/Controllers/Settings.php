<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\IncomeCategories;
use \App\Models\ExpenseCategories;
use \App\Models\User;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Controllers\Signin;

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
		$categories=static::loadCategories("incomes");
		View::renderTemplate('Settings/incomes.html', ['categories' => $categories]);
    }
	
	public function expensesAction()
    {
		View::renderTemplate('Settings/expenses.html');
    }
	
	public function paymenthsAction()
    {
		View::renderTemplate('Settings/paymenths.html');
    }
	
	public function deleteUserAction()
    {
		$user = new User();
		$user = $user -> findByID($_SESSION['loggedUserId']);
		$user -> delteUser( $_SESSION['loggedUserId'] );
		$this ->redirect('/signin/loggout');
    }
	
	public function changePasswordAction()
	{
		$user = new User();
		$user = $user -> findByID($_SESSION['loggedUserId']);
		$password_hash = password_hash($_POST['newPassword1'], PASSWORD_DEFAULT);
		$user -> updatePassword( $_SESSION['loggedUserId'] , $password_hash );
	}
	
	public static function loadCategories( $categoriesSpecify )
	{
		$userId=$_SESSION['loggedUserId'];
		if( $categoriesSpecify == "incomes")
		{
			$incomeCategories = new IncomeCategories($userId);
			$categories=$incomeCategories-> getCategoriesNameByUserId($userId) ;
		}
		else if( $categoriesSpecify == "expenses" )
		{
			$expenseCategories = new ExpenseCategories($userId);
			$categories=$expenseCategories-> getCategoriesNameByUserId($userId) ;
		}
		else if( $categoriesSpecify == "payments" )
		{
			$paymentsMethods = new PaymentsMethods($userId);
			$categories=$paymentsMethods-> getMethodsById($userId) ;
		}
		else
		{
			$categories="Something gone wrong";
		}
		return $categories;
	}
	
	public function updateIncomeCategoryNameAction()
	{
		$userId=$_SESSION['loggedUserId'];
		$incomeCategories = new IncomeCategories($userId);
		$incomeCategories -> updateCategoryName($_POST['selectedCategoryName'] , $_POST['newCategoryName'] );
	}
	
	public function deleteCategoryAction()
	{
		$userId=$_SESSION['loggedUserId'];
		$incomeCategories = new IncomeCategories($userId);
		$idDeletedCategory = $incomeCategories -> getCategoryId( $_POST['selectedCategoryName'] );
		$anotherName = 'Another';
		$idAnotherCategory = $incomeCategories -> getCategoryId($anotherName);
		
		$income= new Income;
		$income -> changeIncomeCategoryToAnother( $idDeletedCategory , $idAnotherCategory);
		$incomeCategories -> deleteCategory( $_POST['selectedCategoryName'] );
	}
	
	public function newCategoryAction()
	{
		$userId=$_SESSION['loggedUserId'];
		$incomeCategories = new IncomeCategories($userId);
		$incomeCategories -> createNewCategory( $_POST['newCategory'] );
	}
}
