<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Models\Balance;
use \App\Auth;
use \App\Flash;

/**
 * Posts controller
 *
 * PHP Version 7.2
 */
class Posts extends Authenticated
{       
    /**
     * Posts index
     *
     * @return void
     */
    public function indexAction()
    {    
        View::renderTemplate('Posts/index.html');
    }

    /**
     * Add a show an income form
     *
     * @return void
     */
    public function incomeAction()
    {
        View::renderTemplate('Posts/income.html');
    }

    /**
     * Add a show an expense form
     *
     * @return void
     */
    public function expenseAction()
    {
        View::renderTemplate('Posts/expense.html');
    }

    /**
     * Add a show a balance page
     *
     * @return void
     */
    public function balanceAction()
    {
        $balance = new Balance($_POST);
        $beginCurMonth = date("Y-m-01");
        $endCurMonth = date("Y-m-t");	
        $sentence = $balance->setPeriodTime($beginCurMonth, $endCurMonth);
        View::renderTemplate('Posts/balance.html', [
            'sentencePeriod' => $sentence
        ]);
    }

    /**
     * Create an income
     *
     * @return void
     */
    public function createIncomeAction()
    {
        $user = Auth::getUser();
        $income = new Income($_POST);
        
        if ($income->save($user->id)) {

            $this->redirect('/posts/success-income');

        } else {
            Flash::addMessage('Nie udało się zarejestrować przychodu.', Flash::WARNING);

            View::renderTemplate('Posts/income.html', [
                'income' => $income
            ]);
        }
    }

    /**
     * Create an expense
     *
     * @return void
     */
    public function createExpenseAction()
    {
        $user = Auth::getUser();
        $expense = new Expense($_POST);
        
        if ($expense->save($user->id)) {

            $this->redirect('/posts/success-expense');

        } else {
            Flash::addMessage('Nie udało się zarejestrować wydatku.', Flash::WARNING);

            View::renderTemplate('Posts/expense.html', [
                'expense' => $expense
            ]);
        }
    }

    /**
     * Show the income success page
     *
     * @return void
     */

    public function successIncomeAction()
    {
        View::renderTemplate('Posts/s_income.html');
    }

    /**
     * Show the expense success page
     *
     * @return void
     */

    public function successExpenseAction()
    {
        View::renderTemplate('Posts/s_expense.html');
    }

    /**
     * Show the page with expenses and incomes of selected period 
     *
     * @return void
     */
    public function showBalanceAction()
    {
        $balance = new Balance($_POST);
        
        if(!isset($_GET['option'])&&!isset($_POST['dateBegin'])&&!isset($_POST['dateEnd'])){
			$option = 1;
        }
        if(isset($_GET['option'])){
			$option = $_GET['option'];
		}
		if(isset($_POST['dateBegin']) || isset($_POST['dateEnd'])){
			$option = 4;
        }
        
        $date = $balance->selectPeriodTime($option);

        $sentence = $balance->setPeriodTime($date[0], $date[1]);

        if($date[0] == 0){
            Flash::addMessage('Błędny przedział czasowy.', Flash::WARNING);
            $this->redirect('/posts/balance');
        }

        //$sentence = $balance->setPeriodTime($date[0], $date[1]);

        $user = Auth::getUser();

        $income_table = [];

        $i = 0;
        while($i < 4){
           $income_table[$i] = $balance->getSumAmountIncome($user->id, $date[0], $date[1], $i);
           $i++;
        }
    
        View::renderTemplate('Posts/balance.html', [
            'income_table' => $income_table,
            'sentencePeriod' => $sentence

        ]);
    }

       /* 
        if ($balance->getIncomes($user->id, $date1, $date2) && $balance->getExpenses($user->id, $date1, $date2)) {

            $this->redirect('/posts/success-expense');

        } else {
            Flash::addMessage('Nie udało się zarejestrować wydatku.', Flash::WARNING);

            View::renderTemplate('Posts/expense.html', [
                'expense' => $expense
            ]);
        }*/
    

    /**
     * Show the edit page
     *
     * @return void
     */
    /*
    public function editAction()
    {
        echo 'Hello from the edit action in the Posts controller!';
    }
    */
}
