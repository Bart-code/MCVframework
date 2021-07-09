<?php

namespace App\Models;

use PDO;
use \Core\View;

class PaymentMethods extends \Core\Model
{
	protected $userId;

    public function __construct($Id)
    {
		$this->userId=$Id;
    }

    public function save()
    {
		$sql = 'INSERT INTO payment_methods_assigned_to_users(`id`, `user_Id`, `name`) SELECT NULL, :userId, payment_methods_default.name FROM payment_methods_default';
		$db = static::getDB();
        $stmt = $db->prepare($sql);
		
		$stmt->bindValue(':userId', $this->userId, PDO::PARAM_STR);

        return $stmt->execute();
    }
	
	public function getMethodsById($userId)
	{
		$sql = "SELECT * FROM payment_methods_assigned_to_users WHERE user_id = :userId";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

		$stmt->execute();
		if($stmt->rowCount())
		{
			$rowsCount=$stmt->rowCount();
			for($i=0;$i<$rowsCount;$i++)
			{
				$row =  $stmt->fetch(PDO::FETCH_ASSOC);
				$methodsMatrix[$i] = $row['name'];
			}
		}
		else $methodsMatrix[0] = "Something gone wrong";
		return $methodsMatrix;	
	}
	
	public function getpaymentMethodId($name)
	{
		$sql = "SELECT id FROM payment_methods_assigned_to_users 
		WHERE user_id = :userId AND name=:name";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $this -> userId, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		 return  $result['id'];
	}

}
