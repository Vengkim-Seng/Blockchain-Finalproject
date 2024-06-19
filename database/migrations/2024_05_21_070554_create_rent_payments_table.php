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
        Schema::create('rent_payments', function (Blueprint $table) {
            $table->id('rent_payment_id');
            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('lease_id');
            $table->date('payment_date');
            $table->decimal('amount', 10, 2);
            $table->string('proof_of_payment')->nullable();
            // $table->smallInteger('status')->comment('-1 = declined, 0 = pending, 1 = approved')->nullable();
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
        Schema::dropIfExists('rent_payments');
    }
};
