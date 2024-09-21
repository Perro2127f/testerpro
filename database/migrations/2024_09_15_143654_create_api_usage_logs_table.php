<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiUsageLogsTable extends Migration
{
    public function up()
    {
        Schema::create('api_usage_logs', function (Blueprint $table) {
            $table->id();  // id INT AUTO_INCREMENT PRIMARY KEY
            $table->foreignId('user_api_key_id')->constrained('user_api_keys');  // Relación con user_api_keys
            $table->string('name_key', 64);  // Clave API usada
            $table->string('endpoint', 255);  // Endpoint al que se accedió
            $table->integer('endpointFB')->default(0);  // Uso en Facebook
            $table->integer('endpointYT')->default(0);  // Uso en YouTube
            $table->integer('endpointIG')->default(0);  // Uso en Instagram
            $table->integer('fail_use')->default(0);  // Número de fallos
            $table->integer('success_use')->default(0);  // Número de éxitos
            $table->string('ip_address', 45);  // Dirección IP de la solicitud
            $table->timestamp('created_log')->useCurrent();  // Primer uso de la clave
            $table->timestamp('last_use')->useCurrent()->nullable();  // Último uso de la clave
            $table->timestamps();  // created_at y updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('api_usage_logs');
    }
}
