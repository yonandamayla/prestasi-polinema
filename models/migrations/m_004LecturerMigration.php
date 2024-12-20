<?php

use app\cores\Blueprint;
use app\cores\Schema;
use app\models\BaseMigration;

class m_004LecturerMigration implements BaseMigration
{
    public function up(): array
    {
        return Schema::createTableIfNotExist("lecturer", function (Blueprint $table) {
            $table->string("nidn");
            $table->string("name");

            $table->primary("nidn");
        });
    }

    public function down(): array
    {
        return Schema::dropTableIfExist("lecturer");
    }
}