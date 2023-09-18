<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    use HasFactory;
    protected $table = 'verification_codes';
    protected $fillable = [
        'email',
        'code',
        'is_used',
        'expires_at'
    ];

    public function markAsUsed()
    {
        $this->is_used = true;
        $this->save();
    }
}
