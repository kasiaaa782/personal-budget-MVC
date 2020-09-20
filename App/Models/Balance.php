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
     * Incomes
     *
     * @var array
     */
    public $incomes = [];

    /**
     * Expenses
     *
     * @var array
     */
    public $expenses = [];

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
     * Set a period time on page in better format
     *
     * @return string
     */
    public static function setPeriodTime($firstDate, $secondDate)
    {   
        $firstDateFormated = date("d.m.Y", strtotime($firstDate));
        $secondDateFormated = date("d.m.Y", strtotime($secondDate));
        return 'Za okres od '.$firstDateFormated.' do '.$secondDateFormated;
    }

     /**
     * Select a period time to show balance
     *
     * @return array
     */
    public function selectPeriodTime($option)
    {
        $date = [];
        switch($option){
            case 1: 
                //current month
                $beginCurMonth = date("Y-m-01");
                $endCurMonth = date("Y-m-t");				
                $date[0] = $beginCurMonth;
                $date[1] = $endCurMonth;	
                break;
            case 2: 
                //previous month
                $beginPrevMonth = date("Y-m-01", strtotime ("-1 month"));
                $endPrevMonth = date("Y-m-t", strtotime ("-1 month"));				
                $date[0] = $beginPrevMonth;
                $date[1] = $endPrevMonth;	
                break;
            case 3: 
                //current year
                $beginCurYear = date("Y-01-01");
                $endCurYear = date("Y-12-t");				
                $date[0] = $beginCurYear;
                $date[1] = $endCurYear;	
                break;
            case 4: 
                //nonstandard
                $beginDate = $this->dateBegin;
                $endDate = $this->dateEnd;
                if($beginDate >= $endDate || $endDate < $beginDate){
                    $errors[0] = 'Błędny przedział czasowy!';
                    $date[0] = 0;
                    $date[1] = 0;
                } else {
                    $date[0] = $beginDate;
                    $date[1] = $endDate;
                }	
                break;
        }
        return $date;
    }


    /**
     * Fill the income table sum of individual income
     *
     * @return boolean
     */
    public function fillIncomesTable($user_id, $date1, $date2)
    {
        if(empty($this->incomes)) {
            $i = 0;
            while($i < 4){
                $this->incomes[$i] = $this->getSumAmountIncome($user_id, $date1, $date2, $i+1);
                $i++;
            }
            return true;
        }
        return false;
    }

    /**
     * Get sum of individual income as an associative array
     *
     * @return array
     */
    public function getSumAmountIncome($user_id, $date1, $date2, $category)
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
        return  $stmt->fetch();
    }

    /**
     * Get all the expenses as an associative array
     *
     * @return array
     */
    public function getExpenses($user_id, $date1, $date2, $category)
    {
            $sql = 'SELECT amount FROM expenses WHERE user_id = :user_id AND expense_category_assigned_to_user_id = :category AND date_of_expense BETWEEN :date1 AND :date2';
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindValue(':category', $category, PDO::PARAM_INT);
			$stmt->bindValue(':date1', $date1, PDO::PARAM_STR);
			$stmt->bindValue(':date2', $date2, PDO::PARAM_STR);
            $stmt->execute();
            //dostajemy dane amount w szufladkach tablicy asjocjacyjnej o nazwie tablic takich jak w bazie danych
            $expense = $stmt->fetch();
            
            return $expense;
    }
}
