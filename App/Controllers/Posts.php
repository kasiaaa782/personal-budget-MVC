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
        } else if(isset($_GET['option'])){
			$option = $_GET['option'];
		} else if(isset($_POST['dateBegin']) || isset($_POST['dateEnd'])){
			$option = 4;
        }
        $date = $balance->selectPeriodTime($option);
        $sentence = $balance->setPeriodTime($date[0], $date[1]);
        if($date[0] == 0){
            Flash::addMessage('Błędny przedział czasowy.', Flash::WARNING);
            $this->redirect('/posts/balance');
        }

        $user = Auth::getUser();
        
        if($balance->fillIncomesTable($user->id, $date[0], $date[1])){

            View::renderTemplate('Posts/balance.html', [
                'sentencePeriod' => $sentence,
                'balance' => $balance
            ]);
        }

    }

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
