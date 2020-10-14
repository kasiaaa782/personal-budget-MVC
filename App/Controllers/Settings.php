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
   
        if(isset($_POST['submit'])){
            $data = $_POST['data'];
            var_dump($data);
            exit();
        }
        
        View::renderTemplate('Settings/settings.html', [
            'categoriesIncomes' => $categoriesIncomes,
            'categoriesExpenses' => $categoriesExpenses,
            'paymentMethods' => $paymentMethods
        ]);
    }
}     

   