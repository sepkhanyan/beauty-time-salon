<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServiceIdAndDescriptionAndSocialFieldsToSalonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->integer('service_id')
                ->nullable()
                ->after('user_id');
            $table->text('description')->nullable()->after('name');
            $table->string('social')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('salons', function (Blueprint $table) {
            $table->dropColumn('service_id');
            $table->dropColumn('description');
            $table->dropColumn('social');
        });
    }
}
