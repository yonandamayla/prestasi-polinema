<?php

use app\models\BaseMigration;
use app\models\Migration;

class m_034ProvinceChampTrigger extends BaseMigration implements Migration
{
    private string $triggerName = "insert_province_champ";
    private string $insertedTable = "province_champ";
    private string $level = "province";

    public function up(): bool
    {

        return $this->construct->query(
            "IF NOT EXISTS (
                SELECT * 
                FROM sys.triggers 
                WHERE name = '$this->triggerName'
            )
            CREATE TRIGGER $this->triggerName ON prestasi_team
                AFTER INSERT AS
                 BEGIN
                    INSERT INTO $this->insertedTable (id, nim)
                    SELECT
                        NEWID(),
                        i.nim
                    FROM INSERTED AS i
                    JOIN prestasi AS p ON i.prestasi_id = p.id
                    WHERE LOWER(p.competition_level) = '$this->level';
                 END;"
        )->execute();
    }

    public function down(): bool
    {
        return $this->construct->query("DROP TRIGGER $this->triggerName;")->execute();
    }
}