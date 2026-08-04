<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE dynamic_entities MODIFY COLUMN root_category ENUM('dosen', 'mahasiswa', 'alumni', 'fakultas') NOT NULL");
        } else {
            Schema::table('dynamic_entities', function (Blueprint $table) {
                $table->string('root_category')->change();
            });
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE dynamic_entities MODIFY COLUMN root_category ENUM('dosen', 'mahasiswa', 'alumni') NOT NULL");
        }
    }
};
