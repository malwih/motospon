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
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');

            // Informasi proposal
            $table->string('category');
            $table->string('event');
            $table->string('name_community');
            $table->string('name_event');
            $table->string('location');
            $table->date('date_event');
            $table->text('feedback_benefit')->nullable();

            // Simpan isi proposal mentah (optional)
            $table->longText('proposal_raw')->nullable();

            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_completed')->default(false);
            $table->boolean('is_reject')->default(false);
            $table->boolean('submit')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
