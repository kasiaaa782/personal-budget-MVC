<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\Income;
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
     * Create an income
     *
     * @return void
     */
    public function createIncomeAction()
    {
        $user = Auth::getUser();
        $income = new Income($_POST);
        
        if ($income->save($user->id)) {

            $this->redirect('/posts/success');

        } else {
            Flash::addMessage('Nie udało się zarejestrować przychodu.', Flash::WARNING);

            View::renderTemplate('Posts/income.html', [
                'income' => $income
            ]);
        }
    }

    /**
     * Show the income success page
     *
     * @return void
     */

    public function successAction()
    {
        View::renderTemplate('Posts/s_income.html');
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
