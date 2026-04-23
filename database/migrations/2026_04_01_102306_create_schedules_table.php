<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('doctor_id');
            $table->enum('day_of_week', [
                'monday', 'tuesday', 'wednesday',
                'thursday', 'friday', 'saturday', 'sunday'
            ]);
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot_duration_mins')->default(30);
            $table->boolean('is_available')->default(true);

            $table->foreign('doctor_id', 'fk_schedule_doctor')
                  ->references('id')
                  ->on('doctors')
                  ->cascadeOnDelete();

            // end_time > start_time enforced in DB
            // Laravel doesn't support CHECK constraints natively,
            // but MySQL will enforce it from your original SQL.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};