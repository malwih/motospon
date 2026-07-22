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
        Schema::table('proposals', function (Blueprint $table) {
            $table->enum('sponsor_type', ['brand', 'sponsorship'])->default('sponsorship')->after('user_id');
            $table->unsignedBigInteger('brand_id')->nullable()->after('sponsor_type');
            
            $table->foreign('brand_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropColumn(['sponsor_type', 'brand_id']);
        });
    }
};
