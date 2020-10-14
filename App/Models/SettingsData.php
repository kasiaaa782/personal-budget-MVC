<?php

namespace App\Models;

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
    
}
