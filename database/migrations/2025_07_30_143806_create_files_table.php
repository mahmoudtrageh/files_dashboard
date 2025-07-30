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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('original_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->string('extension', 10);
            $table->unsignedBigInteger('size'); // in bytes
            $table->json('metadata')->nullable(); // For storing additional file info
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('uploaded_by');
            $table->boolean('is_public')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_accessed_at')->nullable();
            $table->unsignedBigInteger('download_count')->default(0);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('uploaded_by')->references('id')->on('admins')->onDelete('cascade');

            $table->index(['category_id', 'is_active']);
            $table->index(['mime_type', 'is_active']);
            $table->index(['uploaded_by', 'created_at']);
            $table->fullText(['title', 'description']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
