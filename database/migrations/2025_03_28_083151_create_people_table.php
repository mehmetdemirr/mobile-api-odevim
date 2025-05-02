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
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('age');
            $table->string('surname', 50);
            $table->string('tc')->unique();
            $table->text('address');
            $table->string('phone', 20);
            $table->string('email')->unique();
            $table->date('birth_date');
            $table->char('gender', 1); // E/K
            $table->string('marital_status', 20);
            $table->string('profession', 50);
            $table->string('city', 50);
            $table->string('country', 50);
            $table->string('postal_code', 10);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('people');
    }
};
