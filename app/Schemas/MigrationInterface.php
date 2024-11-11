<?php

namespace App\Schemas;

interface MigrationInterface
{
    public static function up();
    public static function down();
}
