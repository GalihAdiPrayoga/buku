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
        Schema::create('bukus', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('penerbit_id')->nullable()->constrained('penerbits')->onDelete('cascade');
            $table->integer('stok');
            $table->string('pengarang');
            $table->foreignId('kategori_id')->nullable()->constrained('kategoris')->onDelete('cascade');     
            $table->string('cover')->nullable();      
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bukus');
    }
};
