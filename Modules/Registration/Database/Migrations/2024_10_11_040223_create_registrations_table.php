<?php

use App\Database\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (CustomBlueprint $table) {
            $table->id();
            $table->string('nid')->unique();
            $table->string('name')->nullable();
            $table->date('dob')->nullable();
            $table->string('email')->nullable();
            $table->ulid('vaccine_center_id', 'vaccine_centers')->nullable();
            $table->string('status')->nullable();
            $table->date('scheduled_date')->nullable();
            $table->actionAt();
            $table->actionBy();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
