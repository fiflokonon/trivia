<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResetPasswordKey extends Model
{
    use HasFactory;
    protected $table = 'reset_password_keys';
    protected $fillable = [
        'email',
        'key',
        'expires_at'
    ];

    public function markAsUsed()
    {
        $this->is_used = true;
        $this->save();
    }

}
