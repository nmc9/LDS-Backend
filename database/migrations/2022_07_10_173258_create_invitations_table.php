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
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
            ->constrained('users')
            ->onDelete('cascade');
            
            $table->foreignId('event_id')
            ->constrained('events')
            ->onDelete('cascade');

            $table->foreignId('inviter_id')
            ->nullable()
            ->constrained('users')
            ->nullOnDelete();

            $table->tinyInteger('status')->default(0);
            $table->string('token')->nullable();
            $table->text("notes")->nullable();

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
        Schema::dropIfExists('invitation');
    }
};
