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
        Schema::create('skymonitors', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('user_id')->index();
            $table->string('city');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->float('uv_index_threshold');
            $table->float('precipitation_threshold');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('skymonitors');
    }
};
