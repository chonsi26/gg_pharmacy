<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Sends a table's auto-increment counter back to 1, so the next row
 * inserted gets id 1 again. Call it AFTER the table has been emptied and
 * AFTER any surrounding DB::transaction() has committed (MySQL's
 * ALTER TABLE ends a transaction implicitly).
 */
class ResetsAutoIncrement
{
    /** @param string|array<string> $tables */
    public static function run(string|array $tables): void
    {
        $driver = DB::connection()->getDriverName();

        foreach ((array) $tables as $table) {
            if (! Schema::hasTable($table)) {
                continue;
            }

            match ($driver) {
                'mysql', 'mariadb' => DB::statement("ALTER TABLE `{$table}` AUTO_INCREMENT = 1"),
                'sqlite'           => DB::table('sqlite_sequence')->where('name', $table)->delete(),
                'pgsql'            => DB::statement("ALTER SEQUENCE \"{$table}_id_seq\" RESTART WITH 1"),
                default            => null,
            };
        }
    }
}
