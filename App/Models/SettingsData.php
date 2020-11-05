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
     * Get categories of expenses as an associative array
     *
     * @return array
     */
    public function getExpensesCategories() {

		$userID = $this->setUserID();

		$sql = "SELECT ec.name, ec.id, ec.limit_category
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

        $sql = 'INSERT INTO expenses_category_assigned_to_users (user_id, name, limit_category)
                    VALUES (:user_id, :name, 0)';

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

    /**
     * Update income name category in database
     *
     * @return void
     */
    public function updateIncomeCategories($newNameCategory, $idCategory) {
        
        $sql = 'UPDATE incomes_category_assigned_to_users 
                SET name = :name
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $newNameCategory, PDO::PARAM_STR);
        $stmt->bindValue(':id', $idCategory, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Update expense name category in database
     *
     * @return void
     */
    public function updateExpenseCategories($newNameCategory, $idCategory) {
        
        $sql = 'UPDATE expenses_category_assigned_to_users 
                SET name = :name
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $newNameCategory, PDO::PARAM_STR);
        $stmt->bindValue(':id', $idCategory, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Update payment name methods in database
     *
     * @return void
     */
    public function updatePaymentMethods($newNameCategory, $idCategory) {
        
        $sql = 'UPDATE payment_methods_assigned_to_users 
                SET name = :name
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':name', $newNameCategory, PDO::PARAM_STR);
        $stmt->bindValue(':id', $idCategory, PDO::PARAM_INT);

		$stmt->execute();
    }
    
    /**
     * Update id of incomes on 'Other' category in database
     *
     * @return void
     */
    public function updateIdCategoryInIncomes($idRemovedCategory, $idOtherCategory) {

        $userID = $this->setUserID();

        $sql = 'UPDATE incomes 
                SET income_category_assigned_to_user_id = :idOtherCategory
                WHERE user_id = :user_id
                AND income_category_assigned_to_user_id = :idRemovedCategory';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':idOtherCategory', $idOtherCategory, PDO::PARAM_STR);
        $stmt->bindValue(':idRemovedCategory', $idRemovedCategory, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Update id of expenses on 'Other expenses' category in database
     *
     * @return void
     */
    public function updateIdCategoryInExpenses($idRemovedCategory, $idOtherCategory) {

        $userID = $this->setUserID();

        $sql = 'UPDATE expenses 
                SET expense_category_assigned_to_user_id = :idOtherCategory
                WHERE user_id = :user_id
                AND expense_category_assigned_to_user_id = :idRemovedCategory';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':idOtherCategory', $idOtherCategory, PDO::PARAM_STR);
        $stmt->bindValue(':idRemovedCategory', $idRemovedCategory, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $userID, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Insert limits to database 
     *
     * @return void
     */

    public function updateLimitInDB($idCategory, $limit) {

        $sql = 'UPDATE expenses_category_assigned_to_users 
                SET limit_category = :limit
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':id', $idCategory, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Get limits of expenses as an associative array
     *
     * @return array
     */
    public function getExpensesLimits() {

		$userID = $this->setUserID();

		$sql = "SELECT ec.id, ec.limit_category
                FROM expenses_category_assigned_to_users AS ec
                WHERE ec.user_id = $userID
                AND ec.limit_category != 0";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$limitsOfExpenses = $stmt->fetchAll();

		return $limitsOfExpenses;
    }

    /**
     * Set limit of expenses category at 0
     *
     * @return void
     */
    public function resetLimit($idCategory) {

        $sql = "UPDATE expenses_category_assigned_to_users 
                SET limit_category = 0
                WHERE id = :id";

		$db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':id', $idCategory, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Get users to check if users not exist by edition
     *
     * @return array
     */
    public function getUsersMails() {

        $userID = $this->setUserID();

		$sql = "SELECT email FROM users WHERE id != :id ";

		$db = static::getDB();
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':id', $userID, PDO::PARAM_INT);

		$stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Update data of user 
     *
     * @return void
     */

    public function updateUser($newEmail, $newName){
        $userID = $this->setUserID();

        $sql = 'UPDATE users 
                SET username = :username,
                    email = :email
                WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':username', $newName, PDO::PARAM_STR);
        $stmt->bindValue(':email', $newEmail, PDO::PARAM_STR);
        $stmt->bindValue(':id', $userID, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Get password from user 
     *
     * @return void
     */

    public function getPassword(){

        $userID = $this->setUserID();

        $sql = "SELECT password FROM users WHERE id = :id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $userID, PDO::PARAM_INT);

        $stmt->execute();

        $password = $stmt->fetchAll();

        return $password;
    }

    /**
     * Update password of user 
     *
     * @return void
     */

    public function updatePassword($passNew){
        $userID = $this->setUserID();

        $passNew_hash = password_hash($passNew, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = :password WHERE id = :id";

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':id', $userID, PDO::PARAM_INT);
        $stmt->bindValue(':password', $passNew_hash, PDO::PARAM_STR);
        $stmt->execute();
    }

    /**
     * Remove all items from database
     *
     * @return void
     */
    public function removeAllExpensesAndIncomes() {
        $userID = $this->setUserID();

        $sql = "DELETE expenses, incomes 
                FROM expenses, incomes 
                WHERE expenses.user_id = :id 
                AND incomes.user_id = :id";

		$db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $userID, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Remove all categories from database
     *
     * @return void
     */
    public function removeAllCategories() {
        $userID = $this->setUserID();

        $sql = "DELETE e, i, p
                FROM expenses_category_assigned_to_users AS e, 
                    incomes_category_assigned_to_users AS i,
                    payment_methods_assigned_to_users AS p
                WHERE e.user_id = :id 
                AND i.user_id = :id
                AND p.user_id = :id";

		$db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $userID, PDO::PARAM_INT);

		$stmt->execute();
    }

    /**
     * Remove account from database
     *
     * @return void
     */
    public function removeAccount() {
        $userID = $this->setUserID();

        $sql = "DELETE users
                FROM users
                WHERE id = :id";

		$db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $userID, PDO::PARAM_INT);

		$stmt->execute();
    }
}
