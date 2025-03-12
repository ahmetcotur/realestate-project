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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title_tr');
            $table->string('title_en')->nullable();
            $table->text('description_tr');
            $table->text('description_en')->nullable();
            $table->string('location');
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('district')->nullable();
            $table->decimal('price', 12, 2);
            $table->string('currency')->default('TRY');
            $table->integer('bedrooms')->nullable();
            $table->integer('bathrooms')->nullable();
            $table->integer('area')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->foreignId('property_type_id')->constrained()->onDelete('cascade');
            $table->foreignId('agent_id')->nullable()->constrained()->onDelete('set null');
            $table->json('features')->nullable();
            $table->json('images')->nullable();
            $table->string('slug')->unique();
            $table->boolean('is_featured')->default(false);
            $table->string('status')->default('sale'); // sale, rent
            $table->boolean('is_active')->default(true); // görünür/görünmez
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
