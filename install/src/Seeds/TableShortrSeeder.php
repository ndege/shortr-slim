<?php

use Phinx\Seed\AbstractSeed;

class TableShortrSeeder extends AbstractSeed
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
                'slug' => 'test',
                'url'  => 'http://domain.tld',
                'hits' => 1,
                'ip'   => '127.0.0.1'
            ]
        ];
        $shortr = $this->table('shortr');
        $shortr->truncate();
        $shortr->insert($data)->save();
    }
}
