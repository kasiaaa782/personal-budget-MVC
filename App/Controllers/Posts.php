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
        $user_data = Post::getAll();

        View::renderTemplate('Posts/index.html', [
            'user_data' => $user_data,
        ]
    );
    }

    /**
     * Add a new post
     *
     * @return void
     */
    public function newAction()
    {
        echo "new action";
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
