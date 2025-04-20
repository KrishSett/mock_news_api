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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index();
            $table->string('title');
            $table->string('short_desctiprion', 200);
            $table->text('description');
            $table->string('thumbnail')->nullable();
            $table->unsignedBigInteger('subcategory_id')->index();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('subcategory_id')->references('id')->on('subcategories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
