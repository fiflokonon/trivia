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
        'statut_vu'
    ];

    public function discussion()
    {
        return $this->belongsTo(Discussion::class);
    }

    public function markAsRead()
    {
        $this->statut_vu = true;
        $this->save();
    }
}
