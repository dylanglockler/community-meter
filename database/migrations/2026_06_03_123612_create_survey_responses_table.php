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
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();
            $table->string('water_charge_range');
            $table->string('household_size');
            $table->string('separate_charge');
            $table->string('charge_calculation');
            $table->string('charge_increased');
            $table->string('shown_records');
            $table->string('home_ownership');
            $table->string('home_age');
            $table->string('residency_duration');
            $table->text('additional_comments')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
