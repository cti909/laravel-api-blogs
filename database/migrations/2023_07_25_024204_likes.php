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
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger("object_id");
            $table->foreignId('type_id')->nullable()->constrained('type_like')->onDelete('cascade');

            // $table->unsignedBigInteger('type_id');
            // $table->foreign('type_id')
            //     ->references('id')
            //     ->on('type_likes')
            //     ->onDelete('type_likes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
