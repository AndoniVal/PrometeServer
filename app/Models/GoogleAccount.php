<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoogleAccount extends Model
{
    protected $fillable = [
        'user_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
    ];

    protected $casts = [
        'access_token'     => 'encrypted',
        'refresh_token'    => 'encrypted',
        'token_expires_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}