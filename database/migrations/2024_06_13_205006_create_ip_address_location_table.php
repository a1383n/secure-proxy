<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ip_geolocation_temp', function (Blueprint $table) {
            $table->engine('memory');

            $table->ipAddress('ip')->primary();
            $table->string('driver')->nullable();
            $table->string('country_name')->nullable();
            $table->char('currency_code', 3)->nullable();
            $table->char('country_code', 2)->nullable();
            $table->string('region_code', 10)->nullable();
            $table->string('region_name', 100)->nullable();
            $table->string('city_name', 100)->nullable();
            $table->string('zip_code', 20)->nullable();
            $table->char('iso_code', 2)->nullable();
            $table->string('postal_code', 20)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('metro_code', 10)->nullable();
            $table->string('area_code', 10)->nullable();
            $table->string('timezone', 50)->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_geolocation_temp');
    }
};
