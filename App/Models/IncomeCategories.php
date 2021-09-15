<?php

namespace App\Models;

use PDO;
use \Core\View;

class IncomeCategories extends \Core\Model
{
	protected $userId;

    public function __construct($Id)
    {
		$this->userId=$Id;
    }

    public function save()
    {
		$sql = 'INSERT INTO incomes_category_assigned_to_users(`id`, `user_Id`, `name`) SELECT NULL, :userId, incomes_category_default.name FROM incomes_category_default';
		$db = static::getDB();
        $stmt = $db->prepare($sql);
		
		$stmt->bindValue(':userId', $this->userId, PDO::PARAM_STR);

        return $stmt->execute();
    }
	
	public function getCategoriesNameByUserId($userId)
	{
		$sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :userId ORDER BY id DESC; ";

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
				$categoryVector[$i] = $row['name'];
			}
		}
		else $categoryVector[0] = "Something gone wrong";
		return $categoryVector;	
	}
	
	public function getCategoriesIdByUserId($userId)
	{
		$sql = "SELECT * FROM incomes_category_assigned_to_users WHERE user_id = :userId ORDER BY id DESC;";

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
				$categoryVector[$i] = $row['id'];
			}
		}
		else $categoryVector[0] = "Something gone wrong";
		return $categoryVector;	
	}
	
	public function getCategoryId($name)
	{
		$sql = "SELECT id FROM incomes_category_assigned_to_users 
		WHERE user_id = :userId AND name=:name";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':userId', $this -> userId, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return  $result['id'];
	}
	
	public function updateCategoryName( $oldName, $newName)
	{
		$sql = 'UPDATE incomes_category_assigned_to_users 
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
		$sql = 'DELETE FROM incomes_category_assigned_to_users
			WHERE user_id=:userId AND name=:name;';

		$db = static::getDB();
		$stmt = $db->prepare($sql);

		$stmt->bindValue( ':name', $name, PDO::PARAM_STR);
		$stmt->bindValue( ':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->execute();
	}
	
	public function createNewCategory( $name)
	{
		$sql = 'INSERT INTO incomes_category_assigned_to_users VALUES ( NULL , :userId , :name );';

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->bindValue( ':userId', $this -> userId, PDO::PARAM_STR);
		$stmt->bindValue( ':name', $name, PDO::PARAM_STR);
		$stmt->execute();
	}

}
