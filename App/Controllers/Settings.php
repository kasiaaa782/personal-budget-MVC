<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\SettingsData;

/**
 * Balance controller
 *
 * PHP Version 7.2
 */
class Settings extends Authenticated {
    
    /**
     * Add a show a balance page
     *
     * @return void
     */
    public function settingsAction() {
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
    public function removeIncomeCategory() {
       
        $idCategoryToRemove = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;
        
        $settings = new SettingsData();
        $settings->removeIncomeCategoryFromDB($idCategoryToRemove);
    }

    /**
     * Remove expense category from database
     * 
     * @return void
     */
    public function removeExpenseCategory() {
        
        $idCategoryToRemove = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;
        
        $settings = new SettingsData();
        $settings->removeExpenseCategoryFromDB($idCategoryToRemove);
    }

    /**
     * Remove payment category from database
     * 
     * @return void
     */
    public function removePaymentCategory() {
        
        $idCategoryToRemove = isset($_POST['idCategory']) ? $_POST['idCategory'] : NULL;
        
        $settings = new SettingsData();
        $settings->removePaymentCategoryFromDB($idCategoryToRemove);
    }

    
}     

   