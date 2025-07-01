<?php

namespace App\Models;

use Core\App;
use Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $created_at;

    public static function findByEmail(string $email): ?User
    {
        /** @var \Core\Database $db */
        $db = App::get('database');
        $result = $db->fetch(
            query: "SELECT * FROM " . static::$table . " WHERE email = ?",
            params: [$email],
            className: static::class
        );

        return $result ? $result : null;
    }
}
