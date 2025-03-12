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
        Schema::table('properties', function (Blueprint $table) {
            // images kolonu kaldırılıyor çünkü PropertyImage ilişkisiyle çakışıyor
            if (Schema::hasColumn('properties', 'images')) {
                $table->dropColumn('images');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Eğer geri alınırsa, images kolonu eklenecek (JSON tipinde)
            if (!Schema::hasColumn('properties', 'images')) {
                $table->json('images')->nullable();
            }
        });
    }
};
