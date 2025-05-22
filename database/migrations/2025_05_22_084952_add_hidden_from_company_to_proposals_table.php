<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->boolean('hidden_from_company')->default(false);
    });
}

public function down()
{
    Schema::table('proposals', function (Blueprint $table) {
        $table->dropColumn('hidden_from_company');
    });
}

};
