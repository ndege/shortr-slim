<?php

use Phinx\Seed\AbstractSeed;

class TableUsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'user' => 'admin',
                'password'  => password_hash("admin", PASSWORD_BCRYPT),
                'scope' => json_encode([
                    'USE_SHORTR', 'CREATE_CLIENT', 'CHANGE_CLIENT', 'REMOVE_CLIENT'
                ])
            ],
            [
                'user' => 'client',
                'password'  => password_hash("changeme", PASSWORD_BCRYPT),
                'scope' => json_encode([
                    'USE_SHORTR'
                ])
            ]
        ];
        $shortr = $this->table('users');
        $shortr->truncate();
        $shortr->insert($data)->save();
    }
}
