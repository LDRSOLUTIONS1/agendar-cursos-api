<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModelsTable extends Migration
{
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('segment_id')->nullable()->constrained('segments')->onDelete('cascade');
            $table->string('nombre_tipo_unidad')->nullable();
            $table->tinyInteger('estado')
                ->default(2)
                ->comment('0=Eliminado, 1=Inactivo, 2=Activo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('models');
    }
}
