<?php

namespace App\Models;

use PDO;
use \App\User;
use \Core\View;

/**
 * User model
 *
 * PHP version 7.2
 */
class Income extends \Core\Model
{

    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    /**
     * Save the income model with the current property values
     *
     * @return boolean  True if the income was saved, false otherwise
     */
    public function save($user_id)
    {
        $this->validate();
    
        if (empty($this->errors)) {

            $sql = 'INSERT INTO incomes VALUES (NULL,
                        :user_id, 
                        :income_category_assigned_to_user_id, 
                        :amount, 
                        :date_of_income, 
                        :income_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':income_category_assigned_to_user_id', $this->category, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_income', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':income_comment', $this->comment, PDO::PARAM_STR);
            
            return $stmt->execute();
       }

       return false;
    }

    /**
     * Validate current property values, adding validation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        // Amount
        if ($this->amount <= 0) {
            $this->errors[0] = 'Wpisz poprawną kwotę!';
        }

        // Category
        if (!isset($this->category)) {
            $this->errors[1] = 'Wybierz kategorię!';
        }
    }
}