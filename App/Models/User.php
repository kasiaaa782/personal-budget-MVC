<?php

namespace App\Models;

use PDO;
use \App\Token;

/**
 * User model
 *
 * PHP version 7.2
 */
class User extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

       if (empty($this->errors)) {

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (username, password, email)
                    VALUES (:username, :password, :email)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':username', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password_hash, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        if (preg_match('/^[A-ZŁŚ]{1}+[a-ząęółśżźćń]+$/',$this->name) == 0) {
            $this->errors[0] = 'Wpisz poprawne imię zaczynając wielką literą!';
        }

        if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[1] = 'Niepoprawny email!';
        }

        if (static::emailExists($this->email)) {
            $this->errors[1] = 'Konto o takim adresie istnieje, wprowadź inny e-mail!';
        }

        if ((strlen($this->password) < 6)) {
            $this->errors[2] = 'Wpisz co najmniej 6 znaków, jedną literę i jedną cyfrę!' ; 
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[2] = 'Wpisz co najmniej 6 znaków, jedną literę i jedną cyfrę!' ; 
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[2] = 'Wpisz co najmniej 6 znaków, jedną literę i jedną cyfrę!' ; 
        }

    }

    /**
     * See if a user record already exists with the specified email
     *
     * @param string $email email address to search for
     *
     * @return boolean  True if a record already exists with the specified email, false otherwise
     */
    public static function emailExists($email)
    {
        return static::findByEmail($email) !== false;
    }

    /**
     * Find a user model by email address
     *
     * @param string $email email address to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByEmail($email)
    {
        $sql = 'SELECT * FROM users WHERE email = :email';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Authenticate a user by email and password.
     *
     * @param string $email email address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($email, $password)
    {
        $user = static::findByEmail($email);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }

    /**
     * Find a user model by ID
     *
     * @param string $id The user ID
     *
     * @return mixed User object if found, false otherwise
     */
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

    /**
     * Remember the login by inserting a new unique token into the remembered_logins table
     * for this user record
     *
     * @return boolean  True if the login was remembered successfully, false otherwise
     */
    public function rememberLogin()
    {
        $token = new Token();
        $hashed_token = $token->getHash();
        $this->remember_token = $token->getValue();

        $this->expiry_timestamp = time() + 60 * 60 * 24 * 30;  // 30 days from now

        $sql = 'INSERT INTO remembered_logins (token_hash, user_id, expires_at)
                VALUES (:token_hash, :user_id, :expires_at)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':token_hash', $hashed_token, PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':expires_at', date('Y-m-d H:i:s', $this->expiry_timestamp), PDO::PARAM_STR);

        return $stmt->execute();
    }

    /**
     * Add to database default categories for incomes, expenses and methods for payment for current user
     * 
     * @param int $user_id user id to search for
     *
     * @return boolean  True if everything went successfully, false otherwise
     */
    public function addDefaultCategoriesToDB($user_id){
        
        $autoincrement = function($sql){
            $db = static::getDB();
            $stmt1 = $db->prepare($sql);
            $stmt1->execute();
            unset($stmt1);
        };
        
        $db = static::getDB();

        $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name)
                SELECT :user_id AS user_id, name FROM incomes_category_default;
                INSERT INTO expenses_category_assigned_to_users (user_id, name, limit_category)
                SELECT :user_id AS user_id, name, limit_category FROM expenses_category_default;
                INSERT INTO payment_methods_assigned_to_users (user_id, name)
                SELECT :user_id AS user_id, name FROM payment_methods_default
                ';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        unset($stmt);

        $sql_autoincrement1 = "SET @max_id = (SELECT MAX(id) FROM incomes_category_assigned_to_users) + 1;
                SET @sql = CONCAT('ALTER TABLE incomes_category_assigned_to_users AUTO_INCREMENT = ',  @max_id);
                PREPARE stmt FROM @sql;
                EXECUTE stmt;
                ";
        $autoincrement($sql_autoincrement1);

        $sql_autoincrement2 = "SET @max_id = (SELECT MAX(id) FROM expenses_category_assigned_to_users) + 1;
                SET @sql = CONCAT('ALTER TABLE expenses_category_assigned_to_users AUTO_INCREMENT = ',  @max_id);
                PREPARE stmt FROM @sql;
                EXECUTE stmt;
                ";
        $autoincrement($sql_autoincrement2);

        $sql_autoincrement3 = "SET @max_id = (SELECT MAX(id) FROM payment_methods_assigned_to_users) + 1;
                SET @sql = CONCAT('ALTER TABLE payment_methods_assigned_to_users AUTO_INCREMENT = ',  @max_id);
                PREPARE stmt FROM @sql;
                EXECUTE stmt;
                ";
        $autoincrement($sql_autoincrement3);
    }

}