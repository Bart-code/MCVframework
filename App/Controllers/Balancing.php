<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Balance;
use \App\Models\IncomeCategories;
use \App\Models\ExpenseCategories;

class Balancing extends \Core\Controller
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
	
	public function showTemplateAction()
    {
		View::renderTemplate('Balancing/show.html');
    }
	
	public function showAction()
    {
		$selector=$_POST['timeSelector'];
		$downBorder=$_POST['downBorder'];
		$topBorder=$_POST['topBorder'];
		$userId=$_SESSION['loggedUserId'];
		
		$incomeCategories = new IncomeCategories($userId);
		$incomeCategoriesName=$incomeCategories->getCategoriesNameByUserId($userId);
		$incomeCategoriesId=$incomeCategories->getCategoriesIdByUserId($userId);
		
		$expenseCategories = new ExpenseCategories($userId);
		$expenseCategoriesName=$expenseCategories->getCategoriesNameByUserId($userId);
		$expenseCategoriesId=$expenseCategories->getCategoriesIdByUserId($userId);
		
		$balance=new Balance($userId, $downBorder, $topBorder);
		$balance->getSummaryIncomeAmount($incomeCategoriesId);
		$balance->getSummaryExpenseAmount($expenseCategoriesId);
		$balance->setIncomeCategories($incomeCategoriesName);
		$balance->setExpenseCategories($expenseCategoriesName);
		
		View::renderTemplate('Balancing/show.html', [ 'selector' => $selector, 'downBorder' => $downBorder, 'topBorder' =>  $topBorder, 'balance' => $balance]);
    }
}
