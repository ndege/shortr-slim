<?php

use \ShortrSlim\Migrations\Migration;

class CreateTableShortr extends Migration
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
        $table = $this->table('shortr', ['id' => false, 'primary_key' => 'slug']);
        $table->addColumn('slug', 'string', ['limit' => 14]);
        $table->addColumn('url', 'string', ['limit' => 512]);
        $table->addColumn('hits', 'integer', ['default' => 0, 'limit' => 20]);
        $table->addColumn('ip', 'string', ['limit' => 40]);
        // Required for Eloquent's created_at and updated_at columns
        $table->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP']);
        $table->create();
    }
}
