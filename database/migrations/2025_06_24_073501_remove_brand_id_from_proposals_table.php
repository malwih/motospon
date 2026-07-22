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
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['brand_id']);
            // Hapus kolom brand_id
            $table->dropColumn('brand_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proposals', function (Blueprint $table) {
            // Tambahkan kembali kolom brand_id
            $table->unsignedBigInteger('brand_id')->nullable()->after('sponsor_type');
            // Tambahkan foreign key constraint
            $table->foreign('brand_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
