<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToLandlordsTable extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}