<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->string('client_name');
            $table->string('project_title');
            $table->string('slug')->unique()->index();
            $table->text('challenge')->nullable();
            $table->text('approach')->nullable();
            $table->text('results')->nullable();
            $table->json('metrics')->nullable(); // Results highlights metrics JSON
            $table->date('completed_at')->nullable();
            $table->string('status')->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};
