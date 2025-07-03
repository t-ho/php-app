<?php

namespace App\Models;

use App\Core\App;
use App\Core\Model;

class RememberToken extends Model
{
    protected static string $table = 'remember_tokens';
    public const TOKEN_LIFETIME = 30 * 24 * 60 * 60; // 30 days in seconds

    public int $user_id;
    public string $token;
    public string $expires_at;
    public string $created_at;

    public function rotate(): static
    {
        $this->token = self::generateToken();
        $this->expires_at = self::getExpirationDate();
        return $this->save();
    }

    public static function findValid(string $token): ?static
    {
        /** @var \Core\Database $db */
        $db = App::get('database');

        $currentTime = date('Y-m-d H:i:s');

        $result = $db->fetch(
            query: "SELECT * FROM " . static::$table . " WHERE token = ? AND expires_at > ? LIMIT 1",
            params: [$token, $currentTime],
            className: static::class
        );

        return $result ?: null;
    }

    public static function createForUser(int $userId): static
    {
        return static::create([
            'user_id' => $userId,
            'token' => self::generateToken(),
            'expires_at' => self::getExpirationDate(),
        ]);
    }

    private static function generateToken(): string
    {
        return bin2hex(random_bytes(32));
    }

    private static function getExpirationDate(): string
    {
        return date('Y-m-d H:i:s', time() + self::TOKEN_LIFETIME);
    }
}
