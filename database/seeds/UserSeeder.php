<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Admin User',
                'email' => 'admin@tdev.app',
                'password' => password_hash('Test1234', PASSWORD_DEFAULT),
                'role' => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'John Doe',
                'email' => 'john@tdev.app',
                'password' => password_hash('Test1234', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@tdev.app',
                'password' => password_hash('Test1234', PASSWORD_DEFAULT),
                'role' => 'user',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $users = $this->table('users');
        $users->insert($data)->saveData();
    }
}
