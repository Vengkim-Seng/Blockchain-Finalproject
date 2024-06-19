<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromLandlordName20240613 extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            if (Schema::hasColumn('landlords', 'landlord_name')) {
                $table->dropUnique('landlords_landlord_name_unique'); // Drop the unique constraint
            }
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            if (Schema::hasColumn('landlords', 'landlord_name')) {
                $table->unique('landlord_name'); // Add the unique constraint back
            }
        });
    }
}


