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
        Schema::create('filter_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filter_id')->index()->constrained('filters');
            $table->string('pattern');
            $table->enum('pattern_type', ['regex', 'exact', 'wildcard', 'domain']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('filter_items');
    }
};
