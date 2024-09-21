<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiUsageLog extends Model
{
    use HasFactory;

    protected $table = 'api_usage_logs';

    protected $fillable = [
        'user_api_key_id', 
        'name_key', 
        'endpoint', 
        'endpointFB', 
        'endpointYT', 
        'endpointIG', 
        'fail_use', 
        'success_use', 
        'ip_address', 
        'created_log', 
        'last_use'
    ];

    // Relación: Un log pertenece a un usuario
    public function userApiKey()
    {
        return $this->belongsTo(UserApiKey::class, 'user_api_key_id');
    }
}
