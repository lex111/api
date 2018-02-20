<?php


use Phinx\Migration\AbstractMigration;

class CreateCollectionsPresetsTable extends AbstractMigration
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
        $table = $this->table('directus_collection_presets');

        $table->addColumn('title', 'string', [
            'limit' => 128,
            'null' => true,
            'default' => null
        ]);
        $table->addColumn('user', 'integer', [
            'signed' => false,
            'null' => false
        ]);
        $table->addColumn('group', 'integer', [
            'signed' => false,
            'null' => true
        ]);
        $table->addColumn('collection', 'string', [
            'limit' => 64,
            'null' => false
        ]);
        $table->addColumn('fields', 'string', [
            'limit' => 255,
            'null' => true,
            'default' => null
        ]);
        $table->addColumn('statuses', 'string', [
            'limit' => 64,
            'null' => true,
            'default' => null
        ]);
        $table->addColumn('sort', 'string', [
            'limit' => 255,
            'null' => true,
            'default' => null
        ]);
        $table->addColumn('search_string', 'text', [
            'null' => true,
            'default' => null
        ]);
        $table->addColumn('filters', 'text', [
            'null' => true,
            'default' => null
        ]);
        $table->addColumn('view_options', 'text', [
            'null' => true,
            'default' => null
        ]);
        $table->addIndex(['user', 'collection', 'title'], [
            'unique' => true,
            'name' => 'idx_user_collection_title'
        ]);

        $table->create();
    }
}
