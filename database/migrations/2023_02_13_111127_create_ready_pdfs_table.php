<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReadyPdfsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ready_pdfs', function (Blueprint $table) {
            $table->id();
            $table->longText('content');
            $table->string('title');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pdf_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('ready_pdfs');
    }
}
