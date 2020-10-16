<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
use \App\Models\Expense;
use \App\Auth;
use \App\Flash;
use \App\Models\SettingsData;

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
        $categories = new SettingsData();
        $categoriesIncomes = $categories->getIncomesCategories();
        
        View::renderTemplate('Posts/income.html', [
            'categoriesIncomes' => $categoriesIncomes
        ]);
    }

    /**
     * Add a show an expense form
     *
     * @return void
     */
    public function expenseAction()
    {
        $settings = new SettingsData();
        $categoriesExpenses = $settings->getExpensesCategories();
        $paymentMethods = $settings->getPaymentMethods();
        
        View::renderTemplate('Posts/expense.html', [
            'categoriesExpenses' => $categoriesExpenses,
            'paymentMethods' => $paymentMethods
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
            
            $settings = new SettingsData();
            $categoriesIncomes = $settings->getIncomesCategories();
        
            View::renderTemplate('Posts/income.html', [
                'income' => $income,
                'categoriesIncomes' => $categoriesIncomes
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
            
            $settings = new SettingsData();
            $categoriesExpenses = $settings->getExpensesCategories();
            $paymentMethods = $settings->getPaymentMethods();

            View::renderTemplate('Posts/expense.html', [
                'expense' => $expense,
                'categoriesExpenses' => $categoriesExpenses,
                'paymentMethods' => $paymentMethods
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
