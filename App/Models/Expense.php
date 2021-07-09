<?php

namespace App\Models;

use PDO;
use \Core\View;

class Expense extends \Core\Model
{
    public $amountError = "";
	protected $userId, $categoryId, $paymentMethodId=9;

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
	
	public function setUserId($loggedUserId)
	{
		$this->userId=$loggedUserId;
	}
	
	public function setCategoryId($categoryID)
	{
		$this->categoryId=$categoryID;
	}
	
	public function setPaymentMethodId($methodID)
	{
		$this->paymentMethodId=$methodID;
	}

    public function save()
    {

        if( $this->validate() )
		{
			$sql = 'INSERT INTO expenses
                    VALUES ( NULL, :userId, :itemId, :paymentMethodId, :amount, :date, :comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
			$stmt->bindValue(':userId', $this->userId, PDO::PARAM_STR);
			$stmt->bindValue(':itemId', $this->categoryId, PDO::PARAM_STR);
			$stmt->bindValue(':paymentMethodId', $this->paymentMethodId, PDO::PARAM_STR);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':comment', $this->comment, PDO::PARAM_STR);

            return $stmt->execute();
        }
		else return false;
    }
	
    public function validate()
    {
		$amountFloat = (float) $this->amount;
		if( $amountFloat <= 0)
		{
			$this->amountError="Incorrect value of amount";
			return false;
		}
		return true;
    }

}
