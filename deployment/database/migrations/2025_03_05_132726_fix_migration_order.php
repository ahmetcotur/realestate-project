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
        // Önce foreign key kısıtlamalarını kaldır
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['property_type_id']);
            $table->dropForeign(['agent_id']);
        });
        
        Schema::table('property_images', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
        });
        
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
        });

        // Sonra tekrar ekleyelim
        Schema::table('properties', function (Blueprint $table) {
            $table->foreign('property_type_id')->references('id')->on('property_types')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('agents')->onDelete('set null');
        });
        
        Schema::table('property_images', function (Blueprint $table) {
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
        });
        
        Schema::table('contacts', function (Blueprint $table) {
            $table->foreign('property_id')->references('id')->on('properties')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Geri alınacak bir işlem yok
    }
};
