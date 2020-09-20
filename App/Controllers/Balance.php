<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\BalanceData;

/**
 * Balance controller
 *
 * PHP Version 7.2
 */
class Balance extends Authenticated {
    
    
    
    /**
     * Add a show a balance page
     *
     * @return void
     */
    public function balanceAction() {
		$balance = new BalanceData($_POST);

		/*$period = isset($_POST['balance']) ? $_POST['balance'] : "another";
        var_dump($period);
        exit();*/

        
        if (! empty($balance)) {
			$date = $balance->setPeriodTime(1);
			$begin = $date[0];
			$end = $date[1];
		} else {
			$begin = date('Y-m'.'-01');
			$end = date("Y-m-d");
        }

		$incomesGenerally = $balance->getIncomesGenerally($begin, $end);
		$expensesGenerally = $balance->getExpensesGenerally($begin, $end);

        $incomesSum = 0;
        foreach($incomesGenerally as $amountIncome){
            $incomesSum += $amountIncome['SUM(i.amount)'];
        } 

        $expensesSum = 0;
        foreach($expensesGenerally as $expenseIncome){
            $expensesSum += $expenseIncome['SUM(e.amount)'];
        } 
        
		View::renderTemplate('Balance/balance.html', [
            'incomesGenerally' => $incomesGenerally,
            'incomesSum' => $incomesSum,
            'expensesGenerally' => $expensesGenerally,
            'expensesSum' => $expensesSum
        ]);
    }
    
    /**
     * Add a show a balance page
     *
     * @return void
     */
   /* public function balanceAction()
    {
        $beginCurMonth = date("Y-m-01");
        $endCurMonth = date("Y-m-t");	
        $sentence = Balance::setPeriodTime($beginCurMonth, $endCurMonth);
        View::renderTemplate('Posts/balance.html', [
            'sentencePeriod' => $sentence
        ]);
    }*/
}     
   