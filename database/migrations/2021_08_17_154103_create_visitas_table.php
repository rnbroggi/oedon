<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mascota_id')->nullable()->constrained('mascotas');
            $table->datetime('fecha');
            $table->integer('peso')->nullable();
            $table->foreignId('user_veterinario_id')->nullable()->constrained('users');
            $table->text('observaciones')->nullable();
            $table->foreignId('veterinaria_id')->nullable()->constrained('veterinarias');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitas');
    }
}
