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
        Schema::create('collection_reports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('collection_id')->unique(); // one report per collection (one-to-one relationship)
            $table->text('report')->nullable();
            $table->string('pdf_link')->nullable();
            $table->timestamps();

            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_reports');
    }
};
