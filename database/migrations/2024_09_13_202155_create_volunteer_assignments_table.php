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
        Schema::create('volunteer_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('schedule_id');
            $table->enum('task_type', ['Collection', 'Distribution', 'Plumber', 'Cook', 'Driver', 'Mechanic'])->default('Collection');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->enum('assignment_status', ['Assigned', 'In_Progress', 'Completed'])->default('Assigned');
            $table->timestamps();

            $table->foreign('schedule_id')->references('id')->on('volunteer_schedules')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('volunteer_assignments');
    }
};
