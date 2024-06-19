<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromLandlordEmail extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            if (Schema::hasColumn('landlords', 'email')) {
                $table->dropUnique('landlords_email_unique'); // Drop the unique constraint
            }
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            if (Schema::hasColumn('landlords', 'email')) {
                $table->unique('email'); // Add the unique constraint back
            }
        });
    }
}
