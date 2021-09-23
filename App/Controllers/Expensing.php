<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Models\ExpenseCategories;
use \App\Models\PaymentMethods;
use \App\Models\Balance;

class Expensing extends \Core\Controller
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

	public function newAction()
    {
		$categories=static::loadCategories();
		$methods=static::loadPaymentMethods();
		$limits=static::loadCategoryLimits();
		View::renderTemplate('Expensing/new.html', ['paymentMethods' => $methods, 'categories' => $categories, 'limits' => $limits]);
    }
	
	public function addAction()
    {
        $expense= new Expense($_POST);
		$userId=$_SESSION['loggedUserId'];
		$expenseCategories = new ExpenseCategories($userId);
		$paymentMethod = new PaymentMethods($userId);
		
		$categoryId = $expenseCategories -> getCategoryId($expense->item);
		$paymentMethodId = $paymentMethod -> getpaymentMethodId($expense->paymentMethod);
		
		$expense->setUserId($userId);
		$expense->setCategoryId($categoryId);
		$expense->setPaymentMethodId($paymentMethodId);
		
		if( $expense->save() )
		{
			View::renderTemplate('Expensing/success.html');
		}
		else
		{
			$categories=static::loadCategories();
			$paymentMethods=static::loadPaymentMethods();
			$limits=static::loadCategoryLimits();
			View::renderTemplate('Expensing/new.html', ['paymentMethods' =>$paymentMethods,'categories' => $categories, 'limits' => $limits, 'expense' => $expense]);
		}
    }
	
	public static function loadPaymentMethods()
	{
		$userId=$_SESSION['loggedUserId'];
		$paymentMethods = new PaymentMethods($userId);
		$methods=$paymentMethods-> getMethodsById($userId) ;
		return $methods;
	}
	
	public static function loadCategories()
	{
		$userId=$_SESSION['loggedUserId'];
		$expenseCategories = new ExpenseCategories($userId);
		$categories=$expenseCategories-> getCategoriesNameByUserId($userId) ;
		return $categories;
	}
	
	public static function loadCategoryLimits()
	{
		$userId=$_SESSION['loggedUserId'];
		$expenseCategories = new ExpenseCategories($userId);
		$limits=$expenseCategories-> loadLimits($userId) ;
		return $limits;
	}
	
	public function successAction()
    {
        View::renderTemplate('MainSite/mainSite.html');
    }
	
	public function getSummaryExpenseForOneCategoryAction()
	{
		$userId=$_SESSION['loggedUserId'];
		$downBorder = $_POST['downBorder'];
		$topBorder = $_POST['topBorder'];
		$balance=new Balance($userId, $downBorder, $topBorder);
		
		$expenseCategories = new ExpenseCategories($userId);
		$categoryId = $expenseCategories -> getCategoryId($_POST['item']);
		$result = $balance -> getSummaryExpensesAmountsForOneCategory( $categoryId );
		$test=array('id' => $result);
		echo json_encode( $test );
	}

}
