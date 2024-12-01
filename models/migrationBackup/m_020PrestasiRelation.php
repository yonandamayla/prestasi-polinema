<?php

namespace app\models\migrationBackup;

use app\models\BaseMigration;
use app\models\prestasiCore\Attachment;
use app\models\prestasiCore\Prestasi;
use app\models\users\Lecturer;

class m_020PrestasiRelation implements BaseMigration
{
    public function up($db)
    {
        $prestasiTable = Prestasi::TABLE;
        $attachmentIdFK = Prestasi::ATTACHMENT_ID;
        $attachmentTable = Attachment::TABLE;
        $attachmentId = Attachment::ID;
        $supervisorIdFK = Prestasi::SUPERVISOR_ID;
        $lecturerTable = Lecturer::TABLE;
        $nidn = Lecturer::NIDN;

        $tsql = "ALTER TABLE [$prestasiTable] ADD FOREIGN KEY ([$attachmentIdFK]) REFERENCES [$attachmentTable] ([$attachmentId])
                ALTER TABLE [$prestasiTable] ADD FOREIGN KEY ([$supervisorIdFK]) REFERENCES [$lecturerTable] ([$nidn]);";

        return $db->prepare($tsql);
    }

    public function down($db)
    {
    }

}