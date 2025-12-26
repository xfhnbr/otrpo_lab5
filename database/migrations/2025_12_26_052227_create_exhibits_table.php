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
        Schema::create('exhibits', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image_filename');
            $table->integer('user_id')->nullable(); // кто добавил
            $table->integer('museum_id'); // к какому музею относится
            $table->timestamps();
            
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
                  
            $table->foreign('museum_id')
                  ->references('id')
                  ->on('museums')
                  ->onDelete('cascade');
                  
            $table->index('user_id');
            $table->index('museum_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exhibits');
    }
};