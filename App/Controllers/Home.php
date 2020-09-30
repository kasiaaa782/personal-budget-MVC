<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP Version 7.2
 */
class Home extends \Core\Controller
{
    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Home/index.html');
    }

    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        ;
    }

    /**
     * After filter
     *
     * @return void
     */
    protected function after()
    {
        ;
    }
}

