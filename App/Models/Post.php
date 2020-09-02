<?php

namespace App\Models;

use PDO;

/**
 * Post model
 *
 * PHP Version 7.2
 */
class Post extends \Core\Model
{

    /**
     * Get all the posts as an associative array
     *
     * @return array
     */
    public static function getAll()
    {
        try {
            $sql = 'SELECT id, name, email FROM users WHERE id = :user_id';
            
            $db = static::getDB();
            $stmt = $db->prepare($sql); 
            $stmt->bindValue(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
            
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            $stmt->execute();

            return $stmt->fetch();
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
