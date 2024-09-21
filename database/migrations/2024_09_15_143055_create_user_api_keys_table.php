<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserApiKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_api_keys', function (Blueprint $table) {
            $table->id();  // id INT AUTO_INCREMENT PRIMARY KEY
            $table->string('name_key', 64);  // Nombre de la clave API
            $table->string('api_key', 64)->unique();  // Bearer Token único
            $table->integer('credits')->default(0);  // Créditos disponibles por defecto 0
            $table->timestamp('expiration')->nullable();  // Fecha de expiración de la clave
            $table->string('email', 255);  // Correo electrónico
            $table->enum('status', ['active', 'inactive', 'vencido', 'suspendido', 'eliminado'])->default('active');  // Estado de la clave
            $table->string('ip_address', 45)->default('0.0.0.0');  // IP permitida
            $table->timestamps();  // created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_api_keys');
    }
}
