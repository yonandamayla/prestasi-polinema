<?php

use app\cores\Blueprint;
use app\cores\Schema;
use app\models\BaseMigration;

class m_022AttachmentRelation implements BaseMigration
{
    public function up(): array
    {
        return Schema::alterTable("attachment", function (Blueprint $table) {
            $table->alterAddForeignKey("loa_id", "loa", "id", "fk_loa_id_attachment");
        });
    }

    public function down(): array
    {
        return [];
    }
}