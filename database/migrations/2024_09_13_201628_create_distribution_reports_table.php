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
        Schema::create('distribution_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('distribution_id')->unique(); // one report per distribution (one-to-one relationship)
            $table->text('report')->nullable();
            $table->string('pdf_link')->nullable();
            $table->timestamps();

            $table->foreign('distribution_id')->references('id')->on('distributions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distribution_reports');
    }
};
