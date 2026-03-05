<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('postal_code', 8)->nullable()->after('email');
            $table->string('address', 255)->nullable()->after('postal_code');
            $table->string('building', 255)->nullable()->after('address');
            $table->string('avatar_path', 255)->nullable()->after('building');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['postal_code', 'address', 'building', 'avatar_path']);
        });
    }
};
