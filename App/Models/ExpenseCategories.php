<?php

namespace App\Models;

use PDO;
use \Core\View;

class ExpenseCategories extends \Core\Model
{
	protected $userId;

    public function __construct($Id)
    {
		$this->userId=$Id;
    }

    public function save()
    {
		$sql = 'INSERT INTO expenses_category_assigned_to_users(`id`, `user_Id`, `name`) SELECT NULL, :userId, expenses_category_default.name FROM expenses_category_default';
		$db = static::getDB();
        $stmt = $db->prepare($sql);
		
		$stmt->bindValue(':userId', $this->userId, PDO::PARAM_STR);

        return $stmt->execute();
    }
	
	public function getCategoriesNameByUserId($userId)
	{
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :userId";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $userId, PDO::PARAM_STR);

		$stmt->execute();
		if($stmt->rowCount())
		{
			$rowsCount=$stmt->rowCount();
			for( $i=0 ; $i < $rowsCount ; $i++ )
			{
				$row =  $stmt->fetch(PDO::FETCH_ASSOC);
				$categoryMatrix[$i] = $row['name'];
			}
		}
		else $categoryMatrix[0] = "Something gone wrong";
		return $categoryMatrix;	
	}
	
	public function getCategoriesIdByUserId($userId)
	{
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :userId";

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
				$categoryVector[$i] = $row['id'];
			}
		}
		else $categoryVector[0] = "Something gone wrong";
		return $categoryVector;	
	}
	
	public function getCategoryId($name)
	{
		$sql = "SELECT id FROM expenses_category_assigned_to_users 
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
