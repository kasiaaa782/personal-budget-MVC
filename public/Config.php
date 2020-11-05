<?php

namespace App;

/**
 * Application configuration
 *
 * PHP Version 7.2
 */
class Config
{

    /**
     * Database host
     * @var string
     */
    const DB_HOST = 'localhost';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'katarz15_personalbudget';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'katarz15_PersonalBudgetAdmin';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = 'PersonalBudget!';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;

    /**
     * Secret key for hashing
     * @var boolean
     */
    const SECRET_KEY = 'ZlBlZ3dncta3UamcKJI7RupIHUZsvDTf';
}
