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

        /*$sql = "SELECT icd.name, SUM(i.amount) 
        FROM incomes AS i, 
            incomes_category_default AS icd
        WHERE i.user_id='$userID' 
        AND icd.id=i.income_category_assigned_to_user_id 
        GROUP BY income_category_assigned_to_user_id";*/
        $sql = "SELECT name, id 
                FROM incomes_category_default"; 

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

		/*$sql = "SELECT ecd.name, SUM(e.amount) 
                FROM expenses AS e,
                    expenses_category_default AS ecd
                WHERE e.user_id='$userID' 
                AND ecd.id=e.expense_category_assigned_to_user_id 
                GROUP BY expense_category_assigned_to_user_id";*/
        $sql = "SELECT name FROM expenses_category_default";

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

		/*$sql = "SELECT ecd.name, SUM(e.amount) 
                FROM expenses AS e,
                    expenses_category_default AS ecd
                WHERE e.user_id='$userID' 
                AND ecd.id=e.expense_category_assigned_to_user_id 
                GROUP BY expense_category_assigned_to_user_id";*/
        $sql = "SELECT name FROM payment_methods_default";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$paymentMethods = $stmt->fetchAll();

		return $paymentMethods;
    }
}
