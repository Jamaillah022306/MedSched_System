<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->integer('patient_id');
            $table->integer('doctor_id');
            $table->integer('schedule_id')->nullable();
            $table->date('appointment_date');
            $table->time('appointment_time');
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])
                  ->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            // Foreign keys
            $table->foreign('patient_id', 'fk_appointment_patient')
                  ->references('id')
                  ->on('patients')
                  ->cascadeOnDelete();

            $table->foreign('doctor_id', 'fk_appointment_doctor')
                  ->references('id')
                  ->on('doctors')
                  ->cascadeOnDelete();

            $table->foreign('schedule_id', 'fk_appointment_schedule')
                  ->references('id')
                  ->on('schedules')
                  ->nullOnDelete();

            // No double-booking
            $table->unique(
                ['doctor_id', 'appointment_date', 'appointment_time'],
                'uq_doctor_slot'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};