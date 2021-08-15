<?php

namespace App\Models;

use PDO;
use \Core\View;

class User extends \Core\Model
{
    public $nameError = "";
    public $lastNameError = "";
    public $emailError = "";
    public $loginError = "";
    public $passwordError = "";

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function save()
    {
        $this->validate();

        if(empty($this->nameError) and empty($this->lastNameError) and empty($this->emailError)
			and empty($this->loginError) and empty($this->passwordError))
		{
            $password_hash = password_hash($this->password1, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users
                    VALUES ( NULL, :name, :lastName, :email, :login, :password_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':lastName', $this->lastName, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':login', $this->login, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }
        return false;
    }

    public function validate()
    {
		// last name
        if ($this->lastName == '')
		{
            $this->lastNameError = 'Last name is required';
        }
		
		// Name
        if ($this->name == '')
		{
            $this->nameError =  'Name is required';
        }

        // email address
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->emailError = 'Invalid email';
        }
        elseif (static::emailExists( $this->email)) {
            $this->emailError = 'Email already exist';
        }
		
		// login
        if ($this->login == '') {
            $this->loginError = 'Login is required';
        }
		elseif (strlen($this->login) < 6)
		{
            $this->loginError = 'Please enter at least 6 characters for the login';
		}
		elseif( static::loginExists( $this->login) )
		{
			$this->loginError = 'Login already exist';
		}

        // Password 
        if (isset($this->password1))
		{
            if (strlen($this->password1) < 6)
			{
                $this->passwordError = 'Please enter at least 6 characters for the password';
            }

            if (preg_match('/.*[a-z]+.*/i', $this->password1) == 0)
			{
                $this->passwordError = 'Password needs at least one letter';
            }

            if (preg_match('/.*\d+.*/i', $this->password1) == 0)
			{
                $this->passwordError = 'Password needs at least one number';
            }
			
			if($this->password1 != $this->password2)
			{
				$this->passwordError = 'Passwords must be same !';
			}
        }
    }

	public static function emailExists($email)
    {
        $user = static::findByEmail($email);
        if ($user != false)
		{
                return true;
        }
        return false;
    }
	
	public static function loginExists($login)
    {
		 $user = static::findByLogin($login);
        if ($user != false)
		{
                return true;
        }
        return false;
    }

    
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        if( $stmt->execute() )
		{
			return $stmt->fetch();
		}
		else return false;
    }
	
	public static function findByLogin($login)
    {
        $sql = 'SELECT * FROM users WHERE login = :login';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
	
	public function getIdByLogin($login)
    {
        $sql = "SELECT * FROM users WHERE login = :login";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':login', $login, PDO::PARAM_STR);
		
        if( $stmt->execute() )
		{
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			return  $user['id'];
		}
		else return 0;
    }

    public  function authenticate($login, $password)
    {
        $user = static::findByLogin($login);
		
		if($user)
		{
			if (password_verify($password, $user->password_hash))
			{
				return true;
			}
			$this->passwordError='Incorrect password';
			return false;
		}
		else
		{
			$this->loginError = 'Incorrect login';
			return false;
		}
    }

    public static function findByID($id)
    {
        $sql = 'SELECT * FROM users WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
	
	public static function getNameByID($id)
    {
       $sql = "SELECT * FROM users WHERE id = :id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
		
        if( $stmt->execute() )
		{
			$user = $stmt->fetch(PDO::FETCH_ASSOC);
			return  $user['name'];
		}
		else return 0;
    }

}
