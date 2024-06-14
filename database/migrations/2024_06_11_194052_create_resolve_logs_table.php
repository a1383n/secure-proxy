<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('resolve_logs', function (Blueprint $table) {
            $table->id();
            $table->ipAddress('client_ip');
            $table->string('domain');
            $table->enum('filter_status', ['allow', 'block', 'bypass']);
            $table->enum('resolve_status', ['resolved', 'failed']);
            $table->ipAddress('resolved_ip')->nullable();
            $table->timestamp('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resolve_logs');
    }
};
