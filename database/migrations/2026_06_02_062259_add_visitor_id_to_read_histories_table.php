<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('read_histories', function (Blueprint $table) {

            $table->string('visitor_id')
                ->nullable()
                ->after('user_id');

        });
    }

    public function down(): void
    {
        Schema::table('read_histories', function (Blueprint $table) {

            $table->dropColumn('visitor_id');

        });
    }
};