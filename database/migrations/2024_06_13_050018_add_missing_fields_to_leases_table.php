<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToLeasesTable extends Migration
{
    public function up()
    {
        Schema::table('leases', function (Blueprint $table) {
            if (!Schema::hasColumn('leases', 'start_date')) {
                $table->date('start_date')->nullable();
            }
            if (!Schema::hasColumn('leases', 'end_date')) {
                $table->date('end_date')->nullable();
            }
            if (!Schema::hasColumn('leases', 'lease_agreement')) {
                $table->text('lease_agreement')->nullable();
            }
            if (!Schema::hasColumn('leases', 'status')) {
                $table->string('status')->nullable();
            }
            if (!Schema::hasColumn('leases', 'version')) {
                $table->integer('version')->nullable();
            }
            if (!Schema::hasColumn('leases', 'previous_record_id')) {
                $table->unsignedBigInteger('previous_record_id')->nullable();
            }
            if (!Schema::hasColumn('leases', 'previous_hash')) {
                $table->string('previous_hash')->nullable();
            }
            if (!Schema::hasColumn('leases', 'current_hash')) {
                $table->string('current_hash')->nullable();
            }
            if (!Schema::hasColumn('leases', 'created_at') && !Schema::hasColumn('leases', 'updated_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('leases', function (Blueprint $table) {
            if (Schema::hasColumn('leases', 'start_date')) {
                $table->dropColumn('start_date');
            }
            if (Schema::hasColumn('leases', 'end_date')) {
                $table->dropColumn('end_date');
            }
            if (Schema::hasColumn('leases', 'lease_agreement')) {
                $table->dropColumn('lease_agreement');
            }
            if (Schema::hasColumn('leases', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('leases', 'version')) {
                $table->dropColumn('version');
            }
            if (Schema::hasColumn('leases', 'previous_record_id')) {
                $table->dropColumn('previous_record_id');
            }
            if (Schema::hasColumn('leases', 'previous_hash')) {
                $table->dropColumn('previous_hash');
            }
            if (Schema::hasColumn('leases', 'current_hash')) {
                $table->dropColumn('current_hash');
            }
            if (Schema::hasColumn('leases', 'created_at') && Schema::hasColumn('leases', 'updated_at')) {
                $table->dropTimestamps();
            }
        });
    }
}
