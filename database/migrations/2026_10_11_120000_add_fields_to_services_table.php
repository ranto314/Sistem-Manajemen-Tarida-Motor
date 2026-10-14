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
        Schema::table('services', function (Blueprint $table) {
            $table->string('kode_jasa')->nullable()->after('id');
            $table->string('kategori_jasa')->nullable()->after('nama_jasa');
            $table->text('keterangan')->nullable()->after('harga_jasa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['kode_jasa', 'kategori_jasa', 'keterangan']);
        });
    }
};
