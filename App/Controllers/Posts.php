<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Post;

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
     * Add a show an income form
     *
     * @return void
     */
    public function createIncomeAction()
    {

    echo "hello"   ; 
    }

    

    /**
     * Show an post
     *
     * @return void
     */
    public function showAction()
    {
        echo "show action";
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
        echo '<p>Route parameters: <pre>' .
             htmlspecialchars(print_r($this->route_params, true)) . '</pre></p>';
    }
    */
}
