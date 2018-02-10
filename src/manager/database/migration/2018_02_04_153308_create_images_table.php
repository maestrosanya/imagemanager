<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_images', function (Blueprint $table) {
            $table->increments('id');

            $table->string('content_type', 255)->nullable()->default('image');
            $table->string('sub_dir', 255)->nullable();
            $table->string('file_name', 255);
            $table->string('original_name', 255);
            $table->string('description', 255)->nullable();
            $table->string('external_id', 255)->nullable();

            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->bigInteger('file_size')->nullable();
            
            $table->integer('folder_id')->unsigned();
            $table->foreign('folder_id')->references('id')->on('m_folders')->onDelete('cascade');

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
        Schema::dropIfExists('m_images');
    }
}
