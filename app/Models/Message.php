<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $fillable = [
        'sender_id',
        'discussion_id',
        'message',
        'statut',
        'vu_client',
        'vu_admin'
    ];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

}
