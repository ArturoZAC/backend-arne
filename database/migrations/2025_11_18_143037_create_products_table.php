<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('slug')->unique();
            $table->string('Titulo01');
            $table->text('Parrafo01');
            $table->string('Subtitulo01');
            $table->string('Imagen01');
            $table->string('Subtitulo02');
            $table->text('Parrafo02');
            $table->string('Imagen02');
            $table->text('Parrafo03');
            $table->string('BotonLink'); // o ->nullable() si prefieres
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
