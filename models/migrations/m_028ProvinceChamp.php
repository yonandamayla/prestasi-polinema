<?php

use app\cores\dbal\ddl\Column;
use app\models\BaseMigration;
use app\models\Migration;

class m_028ProvinceChamp extends BaseMigration implements Migration
{
    public function up(): bool
    {
        return $this->construct->alterTable("province_champ", function (Column $table) {
            $table
                ->addForeignKey("nim", "fk_nim_province_champ")
                ->reference("student","nim")
                ->cascade();
        })->execute();
    }

    public function down(): bool
    {
        return true;
    }
}
