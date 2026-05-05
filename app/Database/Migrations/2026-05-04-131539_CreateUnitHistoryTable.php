<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUnitHistoryTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'auto_increment' => true],
            'unit_id'   => ['type' => 'INT'],
            'pos_x'     => ['type' => 'DECIMAL', 'constraint' => '6,4'],
            'pos_y'     => ['type' => 'DECIMAL', 'constraint' => '6,4'],
            'moved_by'  => ['type' => 'INT', 'null' => true],
            'timestamp' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->createTable('unit_history');
    }
    public function down()
    {
        $this->forge->dropTable('unit_history');
    }
}