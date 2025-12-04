<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    //Probando los commits de este user
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // ID numérico autoincremental

            $table->string('Titulo01')->nullable();
            $table->text('Parrafo01')->nullable();
            $table->string('Subtitulo01')->nullable();
            $table->string('Imagen01')->nullable();
            $table->string('Subtitulo02')->nullable();
            $table->text('Parrafo02')->nullable();
            $table->string('Imagen02')->nullable();
            $table->string('Imagen03')->nullable();
            $table->string('BotonLink')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
