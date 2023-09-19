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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->string('user_id');
            $table->string('house_rent')->nullable()->default(0);
            $table->string('wifi_bill')->nullable()->default(0);
            $table->string('electric_bill')->nullable()->default(0);
            $table->string('radhuni_bill')->nullable()->default(0);
            $table->string('extra_bill')->nullable()->default(0);
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
