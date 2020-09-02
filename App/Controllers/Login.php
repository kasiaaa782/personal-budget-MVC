<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Flash;

/**
 * Login controller
 *
 * PHP version 7.2
 */
class Login extends \Core\Controller
{

    /**
     * Show the login page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Login/new.html');
    }

    /**
     * Log in a user
     *
     * @return void
     */
    public function createAction()
    {
        $user = User::authenticate($_POST['email'], $_POST['password']);
        
        if ($user) {

            session_regenerate_id(true);
            
            $_SESSION['user_id'] = $user->id;

            $this->redirect('/posts/index');

        } else {

            View::renderTemplate('Login/new.html', [
                'email' => $_POST['email'],
                //'remember_me' => $remember_me
            ]);
        }

       /* //$remember_me = isset($_POST['remember_me']);

        if ($user) {

           // Auth::login($user, $remember_me);

            //Flash::addMessage('Login successful');

            //$this->redirect(Auth::getReturnToPage());

            header('Location: http://' .$_SERVER['HTTP_HOST'].'/', true, 303);
            exit();

        } else {

            //Flash::addMessage('Login unsuccessful, please try again', Flash::WARNING);

            View::renderTemplate('Login/new.html');

            //, [
                //'email' => $_POST['email']
                //'remember_me' => $remember_me
           // ]
             
        }*/
    }

    /**
     * Log out a user
     *
     * @return void
     */
    public function destroyAction()
    {
        // Unset all of the session variables.
        $_SESSION = array();

        // If it's desired to kill the session, also delete the session cookie.
        // Note: This will destroy the session, and not just the session data!
        if (ini_get("session.use_cookies")) {
            
            $params = session_get_cookie_params();
            
            setcookie(session_name(),
             '', 
             time() - 42000,
                $params["path"], 
                $params["domain"],
                $params["secure"], 
                $params["httponly"]
            );
        }

        // Finally, destroy the session.
        session_destroy();

        $this->redirect('/');
        
        //Auth::logout();

        //$this->redirect('/login/show-logout-message');
    }

    /**
     * Show a "logged out" flash message and redirect to the homepage. Necessary to use the flash messages
     * as they use the session and at the end of the logout method (destroyAction) the session is destroyed
     * so a new action needs to be called in order to use the session.
     *
     * @return void
     */
    public function showLogoutMessageAction()
    {
        Flash::addMessage('Logout successful');

        $this->redirect('/');
    }
}
