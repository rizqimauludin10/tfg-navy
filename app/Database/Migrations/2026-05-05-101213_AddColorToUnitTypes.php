<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddColorToUnitTypes extends Migration
{
    public function up()
    {
        $this->forge->addColumn('unit_types', [
            'color' => [
                'type'       => 'VARCHAR',
                'constraint' => 7,
                'default'    => '#00cfff',
                'after'      => 'icon_path',
            ],
        ]);
    }
    public function down()
    {
        $this->forge->dropColumn('unit_types', 'color');
    }
}