<?php

namespace App\Models;

use PDO;

/**
 * User model
 *
 * PHP version 7.2
 */
class Expense extends \Core\Model
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
     * Save the expense model with the current property values
     *
     * @return boolean  True if the expense was saved, false otherwise
     */
    public function save($user_id)
    {
        $this->validate();
    
        if (empty($this->errors)) {

            $sql = 'INSERT INTO expenses VALUES (NULL,
                        :user_id, 
                        :expense_category_assigned_to_user_id, 
                        :payment_method_assigned_to_user_id,
                        :amount, 
                        :date_of_expense, 
                        :expense_comment)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $this->amount, PDO::PARAM_STR);
            $stmt->bindValue(':date_of_expense', $this->date, PDO::PARAM_STR);
            $stmt->bindValue(':payment_method_assigned_to_user_id', $this->payment, PDO::PARAM_INT);
            $stmt->bindValue(':expense_category_assigned_to_user_id', $this->category, PDO::PARAM_INT);
            $stmt->bindValue(':expense_comment', $this->comment, PDO::PARAM_STR);
            
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
        
        // Payment
        if (!isset($this->payment)) {
            $this->errors[1] = 'Wybierz sposób płatności!';
        }
        
        // Category
        if (!isset($this->category)) {
            $this->errors[2] = 'Wybierz kategorię!';
        }
    }
}