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
            $table->unsignedBigInteger('filter_list_id');
            $table->string('pattern');
            $table->enum('filter_type', ['regex', 'exact', 'wildcard']);
            $table->foreign('filter_list_id')
                ->references('id')
                ->on('filter_lists')
                ->onDelete('cascade');
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
