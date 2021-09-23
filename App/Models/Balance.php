<?php

namespace App\Models;

use PDO;
use \Core\View;

class Balance extends \Core\Model
{
	protected $userId, $downTimeBorder, $topTimeBorder;
	public $incomesSummaryAmount, $incomeCategoryNames;
	public $expensesSummaryAmount, $expenseCategoryNames;
	public $allIncomes, $allExpenses;

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
	
	public function getAllIncomes()
	{
		$sql="SELECT incomes_category_assigned_to_users.name,
		incomes.amount,
		incomes.date_of_income, incomes.income_comment
		FROM incomes, incomes_category_assigned_to_users
		WHERE incomes.user_id=:userId
		AND incomes_category_assigned_to_users.id=incomes.income_category_assigned_to_user_id
		AND incomes.date_of_income BETWEEN :downBorder
		AND :topBorder"; 
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->bindValue(':downBorder', $this -> downTimeBorder, PDO::PARAM_STR); 
		$stmt->bindValue(':topBorder', $this -> topTimeBorder , PDO::PARAM_STR);
		
		$stmt->execute();
		if($stmt->rowCount())
		{
			$rowsCount=$stmt->rowCount();
			for( $i=0 ; $i < $rowsCount ; $i++ )
			{
				$row =  $stmt->fetch(PDO::FETCH_ASSOC);
				$this -> allIncomes[$i][0] = $row['name'];
				$this -> allIncomes[$i][1] = $row['amount'];
				$this -> allIncomes[$i][2] = $row['date_of_income'];
				$this -> allIncomes[$i][3] = $row['income_comment'];
			}
		}
		else $allIncomes = "No incomes";
	}
	
	public function getAllExpenses()
	{
		$sql="SELECT expenses_category_assigned_to_users.name,
		expenses.amount,
		payment_methods_assigned_to_users.name,
		expenses.date_of_expense, expenses.expense_comment
		FROM expenses, expenses_category_assigned_to_users, payment_methods_assigned_to_users
		WHERE expenses.user_id=:userId
		AND payment_methods_assigned_to_users.id=expenses.payment_method_assigned_to_user_id
		AND expenses_category_assigned_to_users.id=expenses.expense_category_assigned_to_user_id
		AND expenses.date_of_expense BETWEEN :downBorder
		AND :topBorder"; 
		
		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue(':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->bindValue(':downBorder', $this -> downTimeBorder, PDO::PARAM_STR); 
		$stmt->bindValue(':topBorder', $this -> topTimeBorder , PDO::PARAM_STR);
		
		$stmt->execute();
		if($stmt->rowCount())
		{
			$rowsCount=$stmt->rowCount();
			for( $i=0 ; $i < $rowsCount ; $i++ )
			{
				$row =  $stmt->fetch(PDO::FETCH_BOTH);
				$this -> allExpenses[$i][0] = $row[0];
				$this -> allExpenses[$i][1] = $row['amount'];
				$this -> allExpenses[$i][2] = $row[2];
				$this -> allExpenses[$i][3] = $row['date_of_expense'];
				$this -> allExpenses[$i][4] = $row['expense_comment'];
			}
		}
		else $allExpenses = "No incomes";
	}
	
	public function getSummaryExpensesAmountsForOneCategory($expenseCategoriesId)
	{
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
			$stmt->bindValue(':expensesCategoryId', $expenseCategoriesId , PDO::PARAM_STR);
			$stmt->bindValue(':downBorder', $this -> downTimeBorder, PDO::PARAM_STR); 
			$stmt->bindValue(':topBorder', $this -> topTimeBorder , PDO::PARAM_STR);

			$stmt->execute();
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if( $row['summaryAmount'] == null )
			{
				$row['summaryAmount'] = 0;
			}
			return $row['summaryAmount'];
	}
}
