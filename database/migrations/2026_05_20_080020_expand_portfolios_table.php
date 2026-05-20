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
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('before_image_path')->nullable()->after('results');
            $table->string('after_image_path')->nullable()->after('before_image_path');
            $table->json('gallery')->nullable()->after('after_image_path');
            $table->string('video_url')->nullable()->after('gallery');
            $table->json('content')->nullable()->after('video_url');
        });

        Schema::create('category_portfolio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('portfolio_id')->constrained('portfolios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_portfolio');

        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn(['before_image_path', 'after_image_path', 'gallery', 'video_url', 'content']);
        });
    }
};
