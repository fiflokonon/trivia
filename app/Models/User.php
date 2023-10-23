<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nom',
        'email',
        'indicatif',
        'phone',
        'photo_profil',
        'date_naissance',
        'sexe',
        'point_livraison_id',
        'password',
        'verified_email',
        'admin',
        'statut'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function paniers()
    {
        return $this->hasMany(Panier::class);
    }

    public function discussions()
    {
        return $this->hasMany(Discussion::class, 'client_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function viewed_notifications()
    {
        $notifications = $this->notifications();
        foreach ($notifications as $notification){
            $notification->vu = true;
            $notification->save();
        }
    }
}
