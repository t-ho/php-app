<?php

namespace App\Models;

use App\Core\App;
use App\Core\Model;

class User extends Model
{
    protected static string $table = 'users';

    public $id;
    public $name;
    public $email;
    public $password;
    public $role;
    public $created_at;
    public $updated_at;

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

    public static function createUser(array $data): ?User
    {
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);

        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $hashedPassword,
            'role' => 'user'
        ];

        return static::create($userData);
    }

    public static function getRoleDistributionForChart(): array
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        return $db->fetchAll(
            query: "SELECT role, COUNT(*) as count FROM " . static::$table . " 
                    GROUP BY role
                    ORDER BY count DESC",
            params: [],
            className: \stdClass::class
        );
    }
}
