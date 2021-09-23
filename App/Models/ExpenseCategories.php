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
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :userId ORDER BY id DESC;";

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
		$sql = "SELECT * FROM expenses_category_assigned_to_users WHERE user_id = :userId ORDER BY id DESC;";

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
	
	public function loadLimits()
	{
		$sql = "SELECT user_limit FROM expenses_category_assigned_to_users WHERE user_id = :userId ORDER BY id DESC;";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $this -> userId, PDO::PARAM_STR);

		$stmt->execute();
		if($stmt->rowCount())
		{
			$rowsCount=$stmt->rowCount();
			for($i=0;$i<$rowsCount;$i++)
			{
				$row = $stmt->fetch(PDO::FETCH_ASSOC);
				$limitsVector[$i] = $row['user_limit'];
			}
		}
		else $limitsVector[0] = "Something gone wrong";
		return $limitsVector;
	}
	
	public function updateCategoryName( $oldName, $newName)
	{
		$sql = 'UPDATE expenses_category_assigned_to_users 
			SET name=:newName
			WHERE user_id=:userId AND name=:oldName;';

		$db = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue( ':newName', $newName, PDO::PARAM_STR);
		$stmt->bindValue( ':oldName', $oldName, PDO::PARAM_STR);
		$stmt->bindValue( ':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function deleteCategory( $name )
	{
		$sql = 'DELETE FROM expenses_category_assigned_to_users
			WHERE user_id=:userId AND name=:name;';

		$db = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue( ':name', $name, PDO::PARAM_STR);
		$stmt->bindValue( ':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function createNewCategory( $name)
	{
		$sql = 'INSERT INTO expenses_category_assigned_to_users VALUES ( NULL , :userId , :name, "0" );';

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue( ':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->bindValue( ':name', $name, PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function addLimit( $name , $userLimit)
	{
		$sql = 'UPDATE expenses_category_assigned_to_users 
			SET user_limit=:limit
			WHERE user_id=:userId AND name=:name;';

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue( ':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->bindValue( ':name', $name, PDO::PARAM_STR);
		$stmt->bindValue( ':limit', $userLimit, PDO::PARAM_STR);
		$stmt->execute();
	}
}
