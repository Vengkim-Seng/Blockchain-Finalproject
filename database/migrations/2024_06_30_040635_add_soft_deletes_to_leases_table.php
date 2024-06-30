<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSoftDeletesToLeasesTable extends Migration
{
    public function up()
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('leases', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}