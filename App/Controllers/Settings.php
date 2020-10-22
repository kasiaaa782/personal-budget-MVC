<?php

namespace App\Controllers;

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

        View::renderTemplate('Settings/settings.html', [
            'categoriesIncomes' => $categoriesIncomes,
            'categoriesExpenses' => $categoriesExpenses,
            'paymentMethods' => $paymentMethods
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
        $lastId = $settings->getIdLastCategoryIncome();

        echo $lastId; 
    }

    /**
     * Check expense category name
     * 
     * @return void
     */
    public function checkExpenseCategoryAction()
    {
        $settings = new SettingsData();
        $existingCategories = $settings->getExpensesCategories();

        $nameCategory = isset($_POST['categoryName']) ? $_POST['categoryName'] : NULL;
        
        $settings->checkCategoryName($nameCategory, $existingCategories);
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
     * Check payment method name
     * 
     * @return void
     */
    public function checkPaymentMethodAction()
    {
        $settings = new SettingsData();
        $existingCategories = $settings->getPaymentMethods();

        $nameCategory = isset($_POST['categoryName']) ? $_POST['categoryName'] : NULL;

        $settings->checkCategoryName($nameCategory, $existingCategories);
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

}
