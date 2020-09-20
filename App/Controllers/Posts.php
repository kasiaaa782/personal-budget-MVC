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
    
}
