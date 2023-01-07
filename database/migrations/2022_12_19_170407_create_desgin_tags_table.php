<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesginTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('desgin_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desgin_id')->references('id')->on('desgins')->cascadeOnDelete();
            $table->foreignId('tag_id')->nullable()->references('id')->on('tags')->nullOnDelete();
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
        Schema::dropIfExists('desgin_tags');
    }
}
