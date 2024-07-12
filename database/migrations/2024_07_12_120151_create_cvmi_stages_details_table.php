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
        Schema::create('cvmi_stages_details', function (Blueprint $table) {
            $table->id();
            $table->string('stage_name')->nullable();
            $table->string('stage_file')->nullable();
            $table->string('stage_descrption')->nullable();
            $table->string('more_detailed_files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cvmi_stages_details');
    }
};
