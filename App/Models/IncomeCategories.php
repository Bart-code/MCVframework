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
}
