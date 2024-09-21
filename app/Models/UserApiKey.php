<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class UserApiKey extends Model
{
    use HasFactory;

    protected $table = 'user_api_keys';

    protected $fillable = [
        'name_key',
        'api_key',
        'credits',
        'expiration',
        'email',
        'status',
        'ip_address'
    ];

    // Relación: Un usuario puede tener muchos logs de uso
    public function apiUsageLogs()
    {
        return $this->hasMany(ApiUsageLog::class, 'user_api_key_id');
    }
}
