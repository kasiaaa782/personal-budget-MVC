<?php

namespace App\Models;

use PDO;

/**
 * Balance data model
 *
 * PHP Version 7.2
 */
class BalanceData extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Period time
     *
     * @var array
     */
    public $time = [];

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
     * Set a period time to show it in a balance
     *
     * @return array
     */
    public function setPeriodTime($option)
    {
        switch($option){
            case 1: 
                //current month
                $beginCurMonth = date("Y-m-01");
                $endCurMonth = date("Y-m-t");				
                $time[0] = $beginCurMonth;
                $time[1] = $endCurMonth;	
                break;
            case 2: 
                //previous month
                $beginPrevMonth = date("Y-m-01", strtotime ("-1 month"));
                $endPrevMonth = date("Y-m-t", strtotime ("-1 month"));				
                $time[0] = $beginPrevMonth;
                $time[1] = $endPrevMonth;	
                break;
            case 3: 
                //current year
                $beginCurYear = date("Y-01-01");
                $endCurYear = date("Y-12-t");				
                $time[0] = $beginCurYear;
                $time[1] = $endCurYear;	
                break;
            case 4: 
                //nonstandard
                $beginDate = $this->dateBegin;
                $endDate = $this->dateEnd;
                if($beginDate >= $endDate || $endDate < $beginDate){
                    $errors[0] = 'Błędny przedział czasowy!';
                    $time[0] = 0;
                    $time[1] = 0;
                } else {
                    $time[0] = $beginDate;
                    $time[1] = $endDate;
                }	
                break;
        }
        return $time;
    }

    /**
     * Set a period time on page in better format
     *
     * @return string
     */
    public static function changeFormatDate($firstDate, $secondDate)
    {   
        $firstDateFormated = date("d.m.Y", strtotime($firstDate));
        $secondDateFormated = date("d.m.Y", strtotime($secondDate));
        return 'Za okres od '.$firstDateFormated.' do '.$secondDateFormated;
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
     * Get incomes as an associative array
     *
     * @return array
     */
    public function getIncomesGenerally($beginOfPeriod, $endOfPeriod) {

        $userID = $this->setUserID();

		$sql = "SELECT i.date_of_income, i.income_category_assigned_to_user_id, SUM(i.amount) 
                FROM 
                incomes AS i
                WHERE i.user_id='$userID' 
                AND i.date_of_income BETWEEN '$beginOfPeriod' AND '$endOfPeriod' 
                GROUP BY income_category_assigned_to_user_id";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();
        
        $incomesGenerally = $stmt->fetchAll();

		return $incomesGenerally;
    }
    
    /**
     * Get expenses as an associative array
     *
     * @return array
     */
    public function getExpensesGenerally($beginOfPeriod, $endOfPeriod) {

		$userID = $this->setUserID();

		$sql = "SELECT e.date_of_expense, e.expense_category_assigned_to_user_id, SUM(e.amount) 
                FROM 
                expenses AS e
                WHERE e.user_id='$userID' 
                AND e.date_of_expense BETWEEN '$beginOfPeriod' AND '$endOfPeriod' 
                GROUP BY expense_category_assigned_to_user_id";

		$db = static::getDB();
		$stmt = $db->prepare($sql);
		$stmt->execute();

		$expensesGenerally = $stmt->fetchAll();

		return $expensesGenerally;
    }
}
