<?php

namespace App\Controllers;

use \Core\View;

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
        View::renderTemplate('Settings/settings.html');
    }
}     

   