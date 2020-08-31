<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Signup controller
 *
 * PHP version 7.0
 */
class Signup extends \Core\Controller
{

    /**
     * Show the signup page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Signup/new.html');
    }

    /**
     * Sign up a new user
     *
     * @return void
     */
    public function createAction()
    {
        $user = new User($_POST);

        
        $everything_OK = true;
        $e_rules = "";
        $e_bot = "";

        //Czy zaakceptowano regulamin?
        if(!isset($_POST['rules'])){
            $everything_OK = false;
            $e_rules = "Potwierdź akceptację regulaminu!"; 
        } else {
            $rules = $_POST['rules'];
        }
        
        //sprawdzenie reCAPTCHA
        $secret = '6LcG4MUZAAAAAD8DfRgomSjP8Qi476SbHabT29w1';
        $check = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret.'&response='.$_POST['g-recaptcha-response']);
        $answer = json_decode($check);

        if($answer->success==false){
            $everything_OK = false;
            $e_bot = "Potwierdź, że nie jesteś botem!"; 
        }

        if ($user->save() && $everything_OK = true) {

            //View::renderTemplate('Signup/success.html');
            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/signup/success', true, 303);
            exit;
            //$this->redirect('/signup/success.html');

        } else {

            View::renderTemplate('Signup/new.html', [
                'user' => $user,
                'e_rules' => $e_rules,
                'e_bot' => $e_bot,
                'rules' => $rules
            ]);
        }
    }

    /**
     * Show the signup success page
     *
     * @return void
     */

    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }
}
