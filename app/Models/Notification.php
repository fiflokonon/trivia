<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;
    protected $table = 'notifications';
    protected $fillable = [
        'titre',
        'contenu',
        'vu',
        'user_id',
        'statut'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function viewed(){
        $this->vu = true;
        $this->save();
    }

}
