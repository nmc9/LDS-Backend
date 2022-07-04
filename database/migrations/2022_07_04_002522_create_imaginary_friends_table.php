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
        Schema::create('imaginary_friends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('from_user_id')
            ->constrained('users')
            ->onDelete('cascade');

            $table->string('to_user_email');
            $table->boolean('accepted')->default(false);
            $table->string('token')->nullable();
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
        Schema::dropIfExists('imaginary_friends');
    }
};
