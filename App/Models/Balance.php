<?php

namespace App\Models;

use PDO;

/**
 * Post model
 *
 * PHP Version 7.2
 */
class Balance extends \Core\Model
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
     * Get all the incomes as an associative array
     *
     * @return array
     */
    public static function getIncomes($user_id, $date1, $date2, $category)
    {
            $sql = 'SELECT SUM(amount) FROM incomes WHERE user_id = :user_id AND income_category_assigned_to_user_id = :category AND date_of_income BETWEEN :date1 AND :date2';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindValue(':category', $category, PDO::PARAM_INT);
			$stmt->bindValue(':date1', $date1, PDO::PARAM_STR);
			$stmt->bindValue(':date2', $date2, PDO::PARAM_STR);
            $stmt->execute();
            //dostajemy dane amount w szufladkach tablicy asjocjacyjnej o nazwie tablic takich jak w bazie danych

            return $stmt->fetch();
    }

    /**
     * Get all the expenses as an associative array
     *
     * @return array
     */
    public static function getExpenses($user_id, $date1, $date2, $category)
    {
            $sql = 'SELECT SUM(amount) FROM expenses WHERE user_id = :user_id AND expense_category_assigned_to_user_id = :category AND date_of_expense BETWEEN :date1 AND :date2';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindValue(':category', $category, PDO::PARAM_INT);
			$stmt->bindValue(':date1', $date1, PDO::PARAM_STR);
			$stmt->bindValue(':date2', $date2, PDO::PARAM_STR);
            $stmt->execute();
            //dostajemy dane amount w szufladkach tablicy asjocjacyjnej o nazwie tablic takich jak w bazie danych

            return $stmt->fetch();
    }
}
