<?php

use \ShortrSlim\Migrations\Migration;

class CreateTableTokens extends Migration
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
        $table = $this->table('tokens', ['signed' => false]);
        $table->addColumn('token', 'text');
        $table->addColumn('user_id', 'integer', ['limit' => 11]);
        $table->addColumn('created_at', 'integer', ['limit' => 11]);
        $table->addColumn('expired_at', 'integer', ['limit' => 11]);
        $table->create();
    }
}
