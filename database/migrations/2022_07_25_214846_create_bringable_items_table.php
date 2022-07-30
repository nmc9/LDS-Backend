<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bringable_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('bringable_id')
            ->constrained('bringables')
            ->onDelete('cascade');

            $table->integer('required');
            $table->integer('acquired');

            $table->foreignId('assigned_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bringable_items');
    }
};
