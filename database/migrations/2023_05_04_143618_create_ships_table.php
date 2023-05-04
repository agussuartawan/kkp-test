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
        Schema::create('ships', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->string('owner_name');
            $table->string('owner_address');
            $table->string('ship_size');
            $table->string('captain_name');
            $table->string('member_size');
            $table->string('photo');
            $table->string('licence_number');
            $table->string('licence_doc');
            $table->boolean('is_approve')->nullable();
            $table->text('approval_note')->nullable();
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
        Schema::dropIfExists('ships');
    }
};
