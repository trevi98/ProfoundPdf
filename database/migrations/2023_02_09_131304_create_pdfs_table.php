<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePdfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdfs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('cover');
            $table->text('landmarks')->nullable();
            $table->text('materials')->nullable();
            $table->text('floor_plans')->nullable();
            $table->text('amenities')->nullable();
            $table->text('payment_plan')->nullable();
            $table->text('projections')->nullable();
            $table->mediumText('pages')->nullable();
            $table->foreignId('developer_id')->nullable();
            $table->foreignId('area_id')->nullable();
            $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('pdfs');
    }
}
