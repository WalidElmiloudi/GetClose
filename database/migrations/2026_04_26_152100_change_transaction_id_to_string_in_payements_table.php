<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payements', function (Blueprint $table) {
            $table->string('transaction_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('payements', function (Blueprint $table) {
            $table->integer('transaction_id')->change();
        });
    }
};