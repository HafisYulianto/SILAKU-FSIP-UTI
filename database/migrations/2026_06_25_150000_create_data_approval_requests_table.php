<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('data_approval_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['alumni', 'record']); // jenis data
            $table->enum('action', ['create', 'delete']); // jenis aksi
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            // For dynamic records
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->unsignedBigInteger('record_id')->nullable();

            // For alumni
            $table->unsignedBigInteger('alumni_id')->nullable();

            // Payload — data baru yang akan dibuat (JSON)
            $table->json('payload')->nullable();

            // Who requested this
            $table->unsignedBigInteger('requester_id');
            $table->string('requester_name');
            $table->string('requester_role');

            $table->text('rejection_reason')->nullable();
            $table->timestamps();

            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('entity_id')->references('id')->on('dynamic_entities')->onDelete('cascade');
            $table->foreign('record_id')->references('id')->on('dynamic_records')->onDelete('cascade');
            $table->foreign('alumni_id')->references('id')->on('alumnis')->onDelete('cascade');

            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('data_approval_requests');
    }
};
