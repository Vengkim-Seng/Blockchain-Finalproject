<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToLandlordsTable extends Migration
{
    public function up()
    {
        Schema::table('landlords', function (Blueprint $table) {
            if (!Schema::hasColumn('landlords', 'profile_picture')) {
                $table->string('profile_picture')->nullable();
            }
            if (!Schema::hasColumn('landlords', 'contact_info')) {
                $table->text('contact_info')->nullable();
            }
            if (!Schema::hasColumn('landlords', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('landlords', 'version')) {
                $table->integer('version')->nullable();
            }
            if (!Schema::hasColumn('landlords', 'previous_record_id')) {
                $table->unsignedBigInteger('previous_record_id')->nullable();
            }
            if (!Schema::hasColumn('landlords', 'previous_hash')) {
                $table->string('previous_hash')->nullable();
            }
            if (!Schema::hasColumn('landlords', 'current_hash')) {
                $table->string('current_hash')->nullable();
            }
            if (!Schema::hasColumn('landlords', 'created_at') && !Schema::hasColumn('landlords', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('landlords', function (Blueprint $table) {
            if (Schema::hasColumn('landlords', 'profile_picture')) {
                $table->dropColumn('profile_picture');
            }
            if (Schema::hasColumn('landlords', 'contact_info')) {
                $table->dropColumn('contact_info');
            }
            if (Schema::hasColumn('landlords', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('landlords', 'version')) {
                $table->dropColumn('version');
            }
            if (Schema::hasColumn('landlords', 'previous_record_id')) {
                $table->dropColumn('previous_record_id');
            }
            if (Schema::hasColumn('landlords', 'previous_hash')) {
                $table->dropColumn('previous_hash');
            }
            if (Schema::hasColumn('landlords', 'current_hash')) {
                $table->dropColumn('current_hash');
            }
            if (Schema::hasColumn('landlords', 'created_at') && Schema::hasColumn('landlords', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
}
