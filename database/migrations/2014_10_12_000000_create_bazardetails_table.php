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
        Schema::create('bazardetails', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('join_key');
            $table->text('bazardetails');
            $table->string('amount');
            $table->date('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bazardetails');
    }
};
