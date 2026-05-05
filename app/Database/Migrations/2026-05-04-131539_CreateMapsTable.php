<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMapsTable extends Migration
{
     public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'auto_increment' => true],
            'name'        => ['type' => 'VARCHAR', 'constraint' => 100],
            'image_path'  => ['type' => 'VARCHAR', 'constraint' => 255],
            'is_active'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'uploaded_by' => ['type' => 'INT', 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('maps');
    }
    public function down()
    {
        $this->forge->dropTable('maps');
    }
}