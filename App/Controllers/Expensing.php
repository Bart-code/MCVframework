<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Expense;
use \App\Models\ExpenseCategories;
use \App\Models\PaymentMethods;

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
		View::renderTemplate('Expensing/new.html', ['paymentMethods' => $methods, 'categories' => $categories]);
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
			View::renderTemplate('Expensing/new.html', ['paymentMethods' =>$paymentMethods,'categories' => $categories, 'expense' => $expense]);
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
	
	public function successAction()
    {
        View::renderTemplate('MainSite/mainSite.html');
    }

}
