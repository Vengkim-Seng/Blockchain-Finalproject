<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToTenantsTable extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'profile_picture')) {
                $table->string('profile_picture')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'contact_info')) {
                $table->text('contact_info')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'version')) {
                $table->integer('version')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'previous_record_id')) {
                $table->unsignedBigInteger('previous_record_id')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'previous_hash')) {
                $table->string('previous_hash')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'current_hash')) {
                $table->string('current_hash')->nullable();
            }
            if (!Schema::hasColumn('tenants', 'created_at') && !Schema::hasColumn('tenants', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'profile_picture')) {
                $table->dropColumn('profile_picture');
            }
            if (Schema::hasColumn('tenants', 'contact_info')) {
                $table->dropColumn('contact_info');
            }
            if (Schema::hasColumn('tenants', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('tenants', 'version')) {
                $table->dropColumn('version');
            }
            if (Schema::hasColumn('tenants', 'previous_record_id')) {
                $table->dropColumn('previous_record_id');
            }
            if (Schema::hasColumn('tenants', 'previous_hash')) {
                $table->dropColumn('previous_hash');
            }
            if (Schema::hasColumn('tenants', 'current_hash')) {
                $table->dropColumn('current_hash');
            }
            if (Schema::hasColumn('tenants', 'created_at') && Schema::hasColumn('tenants', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
}
