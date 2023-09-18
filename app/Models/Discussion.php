<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discussion extends Model
{
    use HasFactory;
    protected $table = 'discussions';
    protected $fillable = [
        'sujet',
        'client_id',
        'statut'
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function last_message()
    {
        return $this->messages()->latest();
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'DESC');
    }
}
