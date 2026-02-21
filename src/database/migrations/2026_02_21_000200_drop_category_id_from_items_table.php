<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('items', 'category_id')) {
            return;
        }

        try {
            Schema::table('items', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
            });
        } catch (\Throwable $e) {
            // Ignore if the foreign key does not exist in this environment.
        }

        Schema::table('items', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }

    public function down(): void
    {
        if (Schema::hasColumn('items', 'category_id')) {
            return;
        }

        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('user_id')
                ->constrained()
                ->nullOnDelete();
        });
    }
};
