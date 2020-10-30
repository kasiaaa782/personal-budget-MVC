<?php

namespace App\Controllers;

use App\Models\BalanceData;
use \Core\View;
use \App\Models\SettingsData;

/**
 * Balance controller
 *
 * PHP Version 7.2
 */
class Settings extends Authenticated
{

    /**
     * Add a show a balance page
     *
     * @return void
     */
    public function settingsAction()
    {
        $settings = new SettingsData();

        $categoriesIncomes = $settings->getIncomesCategories();
        $categoriesExpenses = $settings->getExpensesCategories();
        $paymentMethods = $settings->getPaymentMethods();
        $limits = $settings->getExpensesLimits();

        View::renderTemplate('Settings/settings.html', [
            'categoriesIncomes' => $categoriesIncomes,
            'categoriesExpenses' => $categoriesExpenses,
            'paymentMethods' => $paymentMethods,
            'limits' => $limits
        ]);
    }

    /**
     * Remove income category from database
     * 
     * @return void
     */
    public function removeIncomeCategoryAction()
    {
        $idCategoryToRemove = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;

        $settings = new SettingsData();
        $settings->removeIncomeCategoryFromDB($idCategoryToRemove);
    }

    /**
     * Remove expense category from database
     * 
     * @return void
     */
    public function removeExpenseCategoryAction()
    {
        $idCategoryToRemove = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;

        $settings = new SettingsData();
        $settings->removeExpenseCategoryFromDB($idCategoryToRemove);
    }

    /**
     * Remove payment category from database
     * 
     * @return void
     */
    public function removePaymentCategoryAction()
    {
        $idCategoryToRemove = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;

        $settings = new SettingsData();
        $settings->removePaymentCategoryFromDB($idCategoryToRemove);
    }

    /**
     * Get existing categories
     * 
     * @return void
     */
    public function getCategoriesAction()
    {
        $item = isset($_POST['item']) ? $_POST['item'] : NULL;

        $settings = new SettingsData();
        
        switch ($item) {
            case 'Income' : {
                $incomeCategories = $settings->getIncomesCategories();
                echo json_encode($incomeCategories);
                exit;
            }
            case 'Expense' : {
                $expenseCategories = $settings->getExpensesCategories();
                echo json_encode($expenseCategories);
                exit;
            }
            case 'Payment' : {
                $paymentMethods = $settings->getPaymentMethods();
                echo json_encode($paymentMethods);
                exit;
            }
        }
    }

    /**
     * Add income category to database
     * 
     * @return void
     */
    public function addIncomeCategoryAction() 
    {
        $nameCategory = isset($_POST['categoryName']) ? $_POST['categoryName'] : NULL;
        
        $settings = new SettingsData();
        $settings->addIncomeCategoryToDB($nameCategory);
    }

    /**
     * Add expense category to database
     * 
     * @return void
     */
    public function addExpenseCategoryAction() 
    {
        $nameCategory = isset($_POST['categoryName']) ? $_POST['categoryName'] : NULL;
        $settings = new SettingsData();
        $settings->addExpenseCategoryToDB($nameCategory);
    }

    /**
     * Add expense category to database
     * 
     * @return void
     */
    public function addPaymentMethodAction() 
    {
        $nameCategory = isset($_POST['categoryName']) ? $_POST['categoryName'] : NULL;
        $settings = new SettingsData();
        $settings->addPaymentMethodToDB($nameCategory);
    }

    /**
     * Update category name in database
     * 
     * @return void
     */
    public function updateCategoryNameAction()
    {
        $newNameCategory = isset($_POST['newName']) ? $_POST['newName'] : NULL;
        $item = isset($_POST['item']) ? $_POST['item'] : NULL;
        $idCategory = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;

        $settings = new SettingsData();
        
        switch ($item) {
            case 'Income' : {
                $settings->updateIncomeCategories($newNameCategory, $idCategory);
                exit;
            }
            case 'Expense' : {
                $settings->updateExpenseCategories($newNameCategory, $idCategory);
                exit;
            }
            case 'Payment' : {
                $settings->updatePaymentMethods($newNameCategory, $idCategory);
                exit;
            }
        }

    }

    /**
     * Get existing items - expenses or incomes
     * 
     * @return void
     */
    public function getItemsAction()
    {
        $item = isset($_POST['item']) ? $_POST['item'] : NULL;
        $idCategory = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;

        $balance = new BalanceData();
        
        switch ($item) {
            case 'Income' : {
                $incomes = $balance->getIncomes($idCategory);
                echo json_encode($incomes);
                exit;
            }
            case 'Expense' : {
                $expenses = $balance->getExpenses($idCategory);
                echo json_encode($expenses);
                exit;
            }
            case 'Payment' : {
                $methods = [];
                echo json_encode($methods);
                exit;
            }
        }
    }

    /**
     * Update id of category of incomes and expenses of removed category
     * 
     * @return void
     */
    public function assignItemsToOtherCategory() {
        $item = isset($_POST['item']) ? $_POST['item'] : NULL;
        $idRemovedCategory = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;
        $settings = new SettingsData();

        switch ($item) {
            case 'Income' : {
                $categories = $settings->getIncomesCategories();
                foreach ( $categories as $category):
                    if($category[0] == "Inne") {
                        $idOtherCategory = $category[1];
                        $settings->updateIdCategoryInIncomes($idRemovedCategory, $idOtherCategory);
                    }
                endforeach;
                exit;
            }
            case 'Expense' : {
                $categories = $settings->getExpensesCategories();
                foreach ( $categories as $category):
                    if($category[0] == "Inne wydatki") {
                        $idOtherCategory = $category[1];
                        $settings->updateIdCategoryInExpenses($idRemovedCategory, $idOtherCategory);
                    }
                endforeach;
                exit;
            }
        }

    }

    /**
     * set limit for category in database
     * 
     * @return void
     */
    public function setLimitCategory() {
        $limit = isset($_POST['limit']) ? $_POST['limit'] : NULL;
        $idCategory = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;

        $settings = new SettingsData();

        $settings->updateLimitInDB($idCategory, $limit);
    }

    /**
     * Get existing limits of expenses categories
     * 
     * @return void
     */
    public function getLimitsAction()
    {
        $settings = new SettingsData();
        
        $limits = $settings->getExpensesLimits();
        echo json_encode($limits);
    }

    /**
     * Reset limit of category, that means set at 0
     * 
     * @return void
     */
    public function resetLimitCategoryAction()
    {
        $idCategory = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;

        $settings = new SettingsData();
        $settings->resetLimit($idCategory);
    }
}
