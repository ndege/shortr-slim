<?php

use \ShortrSlim\Migrations\Migration;

class CreateTableUsers extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('users', ['signed' => false]);
        $table->addColumn('user', 'string', ['limit' => 64]);
        $table->addColumn('password', 'string', ['limit' => 256]);
        // Required for Eloquent's created_at and updated_at columns
        // $table->addTimestamps();
        $table->addIndex('user', ['unique' => true]);
        $table->create();
    }
}
