<?php

namespace App\Models;

use PDO;
use \Core\View;

class Balance extends \Core\Model
{
	protected $userId, $downTimeBorder, $topTimeBorder;
	public $incomesSummaryAmount, $incomeCategoryNames;
	public $expensesSummaryAmount, $expenseCategoryNames;

    public function __construct( $userId, $downTimeBorder, $topTimeBorder )
    {
        $this->userId=$userId;
        $this->downTimeBorder=$downTimeBorder;
        $this->topTimeBorder=$topTimeBorder;
    }
	
	public function getSummaryIncomeAmount($incomeCategoriesId)
	{
		$categoriesCount = count($incomeCategoriesId);
		for( $i=0 ; $i < $categoriesCount ; $i++ )
		{
			$categoryId = $incomeCategoriesId[ $i ];
			$sql="SELECT SUM(incomes.amount)
			AS summaryAmount
			FROM incomes
			WHERE incomes.user_id=:userId
			AND incomes.income_category_assigned_to_user_id =:incomesCategoryId
			AND incomes.date_of_income BETWEEN :downBorder
			AND :topBorder"; 
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':userId', $this -> userId, PDO::PARAM_STR);
			$stmt->bindValue(':incomesCategoryId', $categoryId , PDO::PARAM_STR);
			$stmt->bindValue(':downBorder', $this -> downTimeBorder, PDO::PARAM_STR); 
			$stmt->bindValue(':topBorder', $this -> topTimeBorder , PDO::PARAM_STR);

			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_BOTH);
			$this -> incomesSummaryAmount[] = $row[0];
		}
	}
	
	public function setIncomeCategories($incomeCategoryNames)
	{
		$incomeCategoryNamesCount=count($incomeCategoryNames);
		for( $i = 0; $i <$incomeCategoryNamesCount ; $i++ )
		{
			$this -> incomeCategoryNames[] = $incomeCategoryNames[ $i ];
		}
	}

	public function getSummaryExpenseAmount($expenseCategoriesId)
	{
		$categoriesCount = count($expenseCategoriesId);
		for( $i=0 ; $i < $categoriesCount ; $i++ )
		{
			$categoryId = $expenseCategoriesId[ $i ];
			$sql="SELECT SUM(expenses.amount)
			AS summaryAmount
			FROM expenses
			WHERE expenses.user_id=:userId
			AND expenses.expense_category_assigned_to_user_id =:expensesCategoryId
			AND expenses.date_of_expense BETWEEN :downBorder
			AND :topBorder"; 
			
			$db = static::getDB();
			$stmt = $db->prepare($sql);
			$stmt->bindValue(':userId', $this -> userId, PDO::PARAM_STR);
			$stmt->bindValue(':expensesCategoryId', $categoryId , PDO::PARAM_STR);
			$stmt->bindValue(':downBorder', $this -> downTimeBorder, PDO::PARAM_STR); 
			$stmt->bindValue(':topBorder', $this -> topTimeBorder , PDO::PARAM_STR);

			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_BOTH);
			$this -> expensesSummaryAmount[] = $row[0];
		}
	}
	
	public function setExpenseCategories($expenseCategoryNames)
	{
		$expenseCategoryNamesCount=count($expenseCategoryNames);
		for( $i = 0; $i <$expenseCategoryNamesCount ; $i++ )
		{
			$this -> expenseCategoryNames[] = $expenseCategoryNames[ $i ];
		}
	}
	
}
