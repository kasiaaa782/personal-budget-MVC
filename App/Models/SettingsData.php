<?php

namespace App\Models;

use PDO;

/**
 * Balance data model
 *
 * PHP Version 7.2
 */
class SettingsData extends \Core\Model
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
     * Get a user id from session
     *     
     * @return string
     */
    public function setUserID () {
		if (isset($_SESSION['user_id'])) {

				return $userID = $_SESSION['user_id'];
			} else {

				return '';
			}
	}

    /**
     * Get categories of incomes as an associative array
     *
     * @return array
     */
    public function getIncomesCategories() {

        $userID = $this->setUserID();

        $sql = "SELECT ic.name, ic.id
                FROM incomes_category_assigned_to_users AS ic 
                WHERE ic.user_id = $userID";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
        
        $incomesCategories = $stmt->fetchAll();

		return $incomesCategories;
    }
    
    /**
     * Get id of last categories of incomes
     *
     * @return int
     */
    public function getIdLastCategoryIncome() {
        $userID = $this->setUserID();

        $sql = "SELECT id FROM incomes_category_assigned_to_users 
                WHERE user_id = $userID ORDER BY id DESC LIMIT 1";

		$db = static::getDB();
		$stmt = $db->prepare($sql);

        $stmt->execute();

        return $stmt->fetchColumn(0);
    }

    /**
     * Get categories of expenses as an associative array
     *
     * @return array
     */
    public function getExpensesCategories() {

		$userID = $this->setUserID();

		$sql = "SELECT ec.name, ec.id
                FROM expenses_category_assigned_to_users AS ec
                WHERE ec.user_id = $userID";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$expensesCategories = $stmt->fetchAll();

		return $expensesCategories;
    }

    /**
     * Get categories of expenses as an associative array
     *
     * @return array
     */
    public function getPaymentMethods() {

        $userID = $this->setUserID();
        
        $sql = "SELECT pm.name, pm.id
                FROM payment_methods_assigned_to_users AS pm
                WHERE pm.user_id = $userID";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$paymentMethods = $stmt->fetchAll();

		return $paymentMethods;
    }

    /**
     * Remove income category from database
     *
     * @return void
     */
    public function removeIncomeCategoryFromDB($idCategory) {

        $sql = "DELETE FROM incomes_category_assigned_to_users
                WHERE id = $idCategory";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
    }

    /**
     * Remove expense category from database
     *
     * @return void
     */
    public function removeExpenseCategoryFromDB($idCategory) {

        $sql = "DELETE FROM expenses_category_assigned_to_users
                WHERE id = $idCategory";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
    }

    /**
     * Remove payment category from database
     *
     * @return void
     */
    public function removePaymentCategoryFromDB($idCategory) {

        $sql = "DELETE FROM payment_methods_assigned_to_users
                WHERE id = $idCategory";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
    }

    /**
     * Check category name
     * 
     * @return void
     */
    public function checkCategoryName($nameCategory, $existingCategories)
    {
        $error = false;

        //Set first big letter and rest small
        $nameCategoryToAdd = ucfirst(strtolower($nameCategory));

        if (!empty($nameCategoryToAdd)) {
            foreach ($existingCategories as $existingCategory) {
                if ($existingCategory[0] == $nameCategoryToAdd) {
                    echo 'Ta nazwa już istnieje!';
                    $error = true;
                }
            }
            if (!$error) {
                //Lack of whitespaces on beginnig and ending of name and start is big letter
                if (preg_match('/^[A-ZĄĘŁÓŻŹXQV]{1}+.*\S$/', $nameCategoryToAdd) == 0) {
                    echo 'Niewłaściwa nazwa - min. 2 znaki, zacznij dużą literą, unikaj spacji na początku i końcu.';
                    $error = true;
                }
            }
            if(!$error){
                echo 'Nazwa poprawna!';
            }
        }
    }

    /**
     * Add income category to database
     *
     * @return void
     */
    public function addIncomeCategoryToDB($nameCategory) {

        $userID = $this->setUserID();

        $sql = 'INSERT INTO incomes_category_assigned_to_users (user_id, name)
                    VALUES (:user_id, :name)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':name', $nameCategory, PDO::PARAM_STR);

		$stmt->execute();
    }

    /**
     * Add expense category to database
     *
     * @return void
     */
    public function addExpenseCategoryToDB($nameCategory) {

        $userID = $this->setUserID();

        $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name)
                    VALUES (:user_id, :name)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':name', $nameCategory, PDO::PARAM_STR);

		$stmt->execute();
    }

    /**
     * Add payment method to database
     *
     * @return void
     */
    public function addPaymentMethodToDB($nameCategory) {

        $userID = $this->setUserID();

        $sql = 'INSERT INTO payment_methods_assigned_to_users (user_id, name)
                    VALUES (:user_id, :name)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':name', $nameCategory, PDO::PARAM_STR);

		$stmt->execute();
    }
    
}
