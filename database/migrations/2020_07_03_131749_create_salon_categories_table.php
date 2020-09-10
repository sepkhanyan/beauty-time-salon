<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalonCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salon_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('salon_id')
                ->constrained()
                ->onDelete('cascade');
            $table->foreignId('category_id')
                ->constrained()
                ->onDelete('cascade');
            $table->text('services');
            $table->integer('sort_id')->nullable();
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
        Schema::dropIfExists('salon_categories');
    }
}
