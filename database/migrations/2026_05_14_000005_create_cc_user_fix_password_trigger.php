<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::connection('oracle')->statement("
            CREATE OR REPLACE TRIGGER cc_user_fix_password
            BEFORE INSERT ON CC_USER
            FOR EACH ROW
            WHEN (NEW.PASSWORD = 'PENDING_SETUP')
            BEGIN
                SELECT ta.PASSWORD INTO :NEW.PASSWORD
                FROM TRADER_APPLICATION ta
                WHERE ta.EMAIL = :NEW.EMAIL
                AND ta.STATUS = 'PENDING'
                AND ROWNUM = 1;
                :NEW.CREATED_AT := SYSDATE;
                :NEW.UPDATED_AT := SYSDATE;
            EXCEPTION
                WHEN NO_DATA_FOUND THEN
                    :NEW.CREATED_AT := SYSDATE;
                    :NEW.UPDATED_AT := SYSDATE;
            END;
        ");
    }

    public function down(): void
    {
        DB::connection('oracle')->statement("DROP TRIGGER cc_user_fix_password");
    }
};
