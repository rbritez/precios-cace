<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMegaOdertasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mega_ofertas', function (Blueprint $table) {
            $table->id();
            $table->string('external_id',100)->nullable();
            $table->string('sku',100)->nullable();
            $table->string('marca',100)->nullable();
            $table->mediumText('link_anterior')->nullable();
            $table->mediumText('link_oferta')->nullable();
            $table->string('titulo',150)->nullable();
            $table->decimal('precio anterior',11,2)->nullable();
            $table->decimal('precio_en_oferta',11,2)->nullable();
            $table->string('categoria',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mega_odertas');
    }
}
