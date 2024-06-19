<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id('utility_bill_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('lease_id');
            $table->date('billing_date');
            $table->json('utilities'); // JSON column to store multiple utility types and readings
            $table->decimal('total_amount', 10, 2);
            $table->string('proof_of_meter_reading');
            $table->string('proof_of_utility_payment')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('tenant_id')->references('tenant_id')->on('tenants')->onDelete('cascade');
            $table->foreign('lease_id')->references('lease_id')->on('leases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_bills');
    }
};
