<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('category_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['item_id', 'category_id']);
        });

        $now = now();
        $rows = DB::table('items')
            ->whereNotNull('category_id')
            ->select(['id as item_id', 'category_id'])
            ->get()
            ->map(fn ($row) => [
                'item_id' => $row->item_id,
                'category_id' => $row->category_id,
                'created_at' => $now,
                'updated_at' => $now,
            ])
            ->all();

        if ($rows !== []) {
            DB::table('category_item')->insert($rows);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('category_item');
    }
};
