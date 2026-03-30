<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Create the pivot table
        Schema::create('categories_relasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_id')->constrained('books')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
        });

        // 2. Migrate existing data
        $books = DB::table('books')->whereNotNull('category_id')->get();
        
        foreach ($books as $book) {
            DB::table('categories_relasi')->insert([
                'book_id' => $book->id,
                'category_id' => $book->category_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // 3. Drop the old column
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // Drop foreign key constraint first
            $table->dropColumn('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Restore the column
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');
        });

        // 2. Restore data (take the first category found for each book)
        $relations = DB::table('categories_relasi')->get();
        
        foreach ($relations as $relation) {
            // Only update if category_id is still null (to avoiding overwriting with multiple categories, just keep one)
            $book = DB::table('books')->where('id', $relation->book_id)->first();
            if ($book && is_null($book->category_id)) {
                DB::table('books')->where('id', $relation->book_id)->update([
                    'category_id' => $relation->category_id
                ]);
            }
        }

        // 3. Drop the pivot table
        Schema::dropIfExists('categories_relasi');
    }
};
