<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_sponsor', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('sponsor_id')->constrained()->onDelete('cascade');
    $table->boolean('is_active')->default(false);
    $table->boolean('is_completed')->default(false);
    $table->boolean('is_reject')->default(false);
    $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_sponsor');
    }
};
