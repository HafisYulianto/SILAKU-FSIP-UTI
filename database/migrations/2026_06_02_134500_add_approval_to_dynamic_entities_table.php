<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dynamic_entities', function (Blueprint $table) {
            $table->enum('approval_status', ['approved', 'pending', 'rejected', 'pending_delete'])
                  ->default('approved')
                  ->after('is_active');
            $table->text('rejection_reason')->nullable()->after('approval_status');

            $table->index('approval_status');
        });
    }

    public function down(): void
    {
        Schema::table('dynamic_entities', function (Blueprint $table) {
            $table->dropIndex(['approval_status']);
            $table->dropColumn(['approval_status', 'rejection_reason']);
        });
    }
};
