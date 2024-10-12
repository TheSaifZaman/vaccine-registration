<?php

use App\Database\CustomBlueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $el = loadConfigData('entity_length');
        Schema::create('vaccine_centers', function (CustomBlueprint $table) use($el){
            $table->id();
            $table->string('name', $el['any_title'])->nullable()->index();
            $table->integer('daily_limit')->nullable();
            $table->text('description')->nullable();
            $table->boolean('status')->default(1)->index();
            $table->string('entity_type', $el['entity_type'])->nullable()->index();
            $table->actionAt();
            $table->actionBy();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_centers');
    }
};
