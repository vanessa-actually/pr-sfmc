<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sfmc_transmissions', function (Blueprint $table) {
            $table->id();

            $table->string('endpoint');
            $table->unsignedInteger('response_status');
            $table->boolean('transmission_status')->nullable();
            $table->string('sfmc_entry_id')->nullable();
            $table->text('transmission_error_message')->nullable();
            $table->text('request_dump')->nullable();
            $table->text('response_dump')->nullable();

            $table->string('transmittable_type')->nullable();
            $table->unsignedBigInteger('transmittable_id')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::create('sfmc_transmissions', function (Blueprint $table) {
            $table->drop(sfmc_transmissions);
        });
    }
};
