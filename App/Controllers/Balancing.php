<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\IncomeCategories;
use \App\Models\ExpenseCategories;

class Balancing extends \Core\Controller
{
    protected function before()
    {
        session_start();
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
		
		View::renderTemplate('Balancing/show.html', [ 'selector' => $selector, 'downBorder' => $downBorder, 'topBorder' =>  $topBorder] );
    }

}
