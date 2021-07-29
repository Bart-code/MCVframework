<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Models\IncomeCategories;

class Incoming extends \Core\Controller
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
		View::renderTemplate('Incoming/new.html', ['categories' => $categories]);
    }
	
	public function addAction()
    {
        $income= new Income($_POST);
		$userId=$_SESSION['loggedUserId'];
		$incomeCategories = new IncomeCategories($userId);
		$categoryId = $incomeCategories -> getCategoryId($income->item);
		$income->setUserId($userId);
		$income->setCategoryId($categoryId);
		
		if( $income->save() )
		{
			View::renderTemplate('Incoming/success.html');
		}
		else
		{
			$categories=static::loadCategories();
			View::renderTemplate('Incoming/new.html', ['categories' => $categories, 'income' => $income]);
		}
    }
	
	public static function loadCategories()
	{
		$userId=$_SESSION['loggedUserId'];
		$incomeCategories = new IncomeCategories($userId);
		$categories=$incomeCategories-> getCategoriesNameByUserId($userId) ;
		return $categories;
	}
	
	public function successAction()
    {
        View::renderTemplate('MainSite/mainSite.html');
    }

}
