<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_filter_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_id')->constrained('client_filters');
            $table->string('name');
            $table->ipAddress();
            $table->enum('type', ['allow', 'block']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_filter_items');
    }
};
