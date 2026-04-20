<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['method', 'endpoint', 'payload', 'ip', 'user_id', 'status_code', 'created_at'];
}
