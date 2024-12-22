<?php


use app\cores\dbal\ddl\Column;
use app\models\BaseMigration;
use app\models\Migration;

class m_003InternationalChampMigration extends BaseMigration implements Migration
{
    public function up(): bool
    {
        return $this->construct->createTable("international_champ", function (Column $table) {
            $table->string("id")->primary();
            $table->string("nim");
        })->execute();
    }

    public function down(): bool
    {
        return $this->construct->dropTable("international_champ")->execute();
    }
}
