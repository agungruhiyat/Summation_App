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
        Schema::create('summations', function (Blueprint $table) {
            $table->uuid("id")->primary();
            $table->string('created_at',13);
            $table->string('updated_at',13);
            $table->string('destination_user_id');
            $table->string('sender_user_id');
            $table->decimal('total',15, 2);
            $table->decimal('rate_per_unit',15, 2);
            $table->string('bank');
            $table->integer('account_number');
            $table->integer('transfer_fees');
            $table->string('processed_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('summations');
    }
};