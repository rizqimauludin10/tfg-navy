<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'auto_increment' => true],
            'name'         => ['type' => 'VARCHAR', 'constraint' => 100],
            'unit_type_id' => ['type' => 'INT', 'null' => true],
            'pos_x'        => ['type' => 'DECIMAL', 'constraint' => '6,4', 'default' => '0.5000'],
            'pos_y'        => ['type' => 'DECIMAL', 'constraint' => '6,4', 'default' => '0.5000'],
            'map_id'       => ['type' => 'INT', 'null' => true],
            'status'       => ['type' => 'ENUM', 'constraint' => ['active','inactive'], 'default' => 'active'],
            'created_by'   => ['type' => 'INT', 'null' => true],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('units');
    }
    public function down()
    {
        $this->forge->dropTable('units');
    }
}