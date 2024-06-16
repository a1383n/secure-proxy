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
        Schema::create('domain_filters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['allow', 'bypass', 'block'])->index();
            $table->boolean('enabled')->default(true)->index();
            $table->timestamps();

            $table->index(['type', 'enabled']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_filters');
    }
};
