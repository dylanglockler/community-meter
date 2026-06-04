<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->text('pressure_to_leave')->nullable()->after('residency_duration');
            $table->string('charges_to_push_out')->nullable()->after('pressure_to_leave');
            $table->text('pressure_description')->nullable()->after('charges_to_push_out');
        });
    }

    public function down(): void
    {
        Schema::table('survey_responses', function (Blueprint $table) {
            $table->dropColumn(['pressure_to_leave', 'charges_to_push_out', 'pressure_description']);
        });
    }
};
