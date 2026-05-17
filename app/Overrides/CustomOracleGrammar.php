<?php

namespace App\Overrides;

use Yajra\Oci8\Schema\Grammars\OracleGrammar as BaseOracleGrammar;

class CustomOracleGrammar extends BaseOracleGrammar
{
    public function compileDropAllTables(): string
    {
        return 'BEGIN
            FOR c IN (SELECT table_name FROM user_tables) LOOP
                BEGIN
                    EXECUTE IMMEDIATE (\'DROP TABLE "\' || c.table_name || \'" CASCADE CONSTRAINTS PURGE\');
                EXCEPTION
                    WHEN OTHERS THEN NULL;
                END;
            END LOOP;
            FOR s IN (SELECT sequence_name FROM user_sequences) LOOP
                EXECUTE IMMEDIATE (\'DROP SEQUENCE \' || s.sequence_name);
            END LOOP;
        END;';
    }
}
